<?php

namespace App\Services;

use App\Models\GoogleAccount;
use App\Models\User;
use Google\Client as GoogleClient;
use Google\Service\SearchConsole;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class GoogleService
{
    protected GoogleClient $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setIncludeGrantedScopes(true);

        // Required scopes for GSC and GA4
        $this->client->addScope([
            'https://www.googleapis.com/auth/webmasters.readonly',
            'https://www.googleapis.com/auth/analytics.readonly',
            'email',
            'profile',
        ]);
    }

    /**
     * Get the OAuth authorization URL.
     */
    public function getAuthUrl(?int $siteId = null): string
    {
        if ($siteId) {
            $this->client->setState(encrypt(['site_id' => $siteId]));
        }

        return $this->client->createAuthUrl();
    }

    /**
     * Exchange authorization code for tokens and save to database.
     */
    public function handleCallback(string $code, User $user): GoogleAccount
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new \Exception('Google OAuth Error: ' . ($token['error_description'] ?? $token['error']));
        }

        $this->client->setAccessToken($token);

        // Get user info from Google
        $oauth2 = new \Google\Service\Oauth2($this->client);
        $googleUser = $oauth2->userinfo->get();

        // Store tokens (encrypted)
        $googleAccount = GoogleAccount::updateOrCreate(
            ['user_id' => $user->id],
            [
                'google_id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'access_token' => Crypt::encryptString($token['access_token']),
                'refresh_token' => isset($token['refresh_token'])
                    ? Crypt::encryptString($token['refresh_token'])
                    : null,
                'token_expires_at' => now()->addSeconds($token['expires_in'] ?? 3600),
                'scopes' => $this->client->getScopes(),
            ]
        );

        return $googleAccount;
    }

    /**
     * Get an authenticated Google Client for a user.
     */
    public function getAuthenticatedClient(User $user): ?GoogleClient
    {
        $googleAccount = $user->googleAccount;

        if (!$googleAccount) {
            return null;
        }

        try {
            $accessToken = Crypt::decryptString($googleAccount->access_token);

            $this->client->setAccessToken([
                'access_token' => $accessToken,
                'expires_in' => now()->diffInSeconds($googleAccount->token_expires_at),
            ]);

            // Check if token is expired and refresh if needed
            if ($this->client->isAccessTokenExpired()) {
                if ($googleAccount->refresh_token) {
                    $refreshToken = Crypt::decryptString($googleAccount->refresh_token);
                    $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

                    if (!isset($newToken['error'])) {
                        $googleAccount->update([
                            'access_token' => Crypt::encryptString($newToken['access_token']),
                            'token_expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600),
                        ]);

                        $this->client->setAccessToken($newToken);
                    } else {
                        Log::error('Failed to refresh Google token', $newToken);
                        return null;
                    }
                } else {
                    return null;
                }
            }

            return $this->client;
        } catch (\Exception $e) {
            Log::error('Google auth error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch Search Console properties (domain properties only).
     */
    public function getSearchConsoleProperties(User $user): array
    {
        $client = $this->getAuthenticatedClient($user);

        if (!$client) {
            throw new \Exception('Google account not connected or token expired. Please reconnect.');
        }

        try {
            $service = new SearchConsole($client);
            $sites = $service->sites->listSites();

            $domainProperties = [];

            foreach ($sites->getSiteEntry() as $site) {
                $siteUrl = $site->getSiteUrl();

                // Only include domain properties (sc-domain:)
                if (str_starts_with($siteUrl, 'sc-domain:')) {
                    $domainProperties[] = [
                        'url' => $siteUrl,
                        'domain' => str_replace('sc-domain:', '', $siteUrl),
                        'permission_level' => $site->getPermissionLevel(),
                    ];
                }
            }

            return $domainProperties;
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            $message = $error['error']['message'] ?? 'Failed to fetch Search Console properties';
            throw new \Exception($message);
        }
    }

    /**
     * Fetch GA4 properties.
     */
    public function getGA4Properties(User $user): array
    {
        $client = $this->getAuthenticatedClient($user);

        if (!$client) {
            throw new \Exception('Google account not connected or token expired. Please reconnect.');
        }

        try {
            $service = new \Google\Service\GoogleAnalyticsAdmin($client);
            $accounts = $service->accountSummaries->listAccountSummaries();

            $properties = [];

            foreach ($accounts->getAccountSummaries() as $account) {
                foreach ($account->getPropertySummaries() as $property) {
                    $properties[] = [
                        'id' => $property->getProperty(), // e.g., "properties/123456789"
                        'name' => $property->getDisplayName(),
                        'account' => $account->getDisplayName(),
                    ];
                }
            }

            return $properties;
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            $message = $error['error']['message'] ?? 'Failed to fetch GA4 properties';
            throw new \Exception($message);
        }
    }

    /**
     * Check if a user has access to a specific GSC domain property.
     */
    public function hasGscAccess(User $user, string $domain): bool
    {
        try {
            $properties = $this->getSearchConsoleProperties($user);
            $gscDomain = 'sc-domain:' . $domain;

            foreach ($properties as $property) {
                if ($property['url'] === $gscDomain) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if a user has access to a specific GA4 property.
     */
    public function hasGa4Access(User $user, string $propertyId): bool
    {
        try {
            $properties = $this->getGA4Properties($user);

            foreach ($properties as $property) {
                if ($property['id'] === $propertyId) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Disconnect Google account for a user.
     */
    public function disconnect(User $user): void
    {
        $googleAccount = $user->googleAccount;

        if ($googleAccount) {
            // Try to revoke the token
            try {
                $client = $this->getAuthenticatedClient($user);
                if ($client) {
                    $client->revokeToken();
                }
            } catch (\Exception $e) {
                // Token revocation failed, but we'll still delete the record
                Log::warning('Failed to revoke Google token: ' . $e->getMessage());
            }

            $googleAccount->delete();
        }
    }

    /**
     * Check if a user has a connected Google account.
     */
    public function isConnected(User $user): bool
    {
        return $user->googleAccount !== null;
    }

    /**
     * Get the connected Google account email.
     */
    public function getConnectedEmail(User $user): ?string
    {
        return $user->googleAccount?->email;
    }
}
