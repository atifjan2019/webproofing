<?php

namespace App\Services\Google;

use App\Models\GoogleAccount;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleTokenManager
{
    /**
     * Get a valid access token for the user, refreshing if necessary.
     */
    public function getValidAccessToken(User $user): ?string
    {
        $googleAccount = $user->googleAccount;

        if (!$googleAccount) {
            return null;
        }

        // If token is still valid, return it
        if (!$googleAccount->isTokenExpired()) {
            return $googleAccount->access_token;
        }

        // Token is expired, try to refresh
        if (!$googleAccount->hasRefreshToken()) {
            Log::warning('Google token expired and no refresh token available', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        return $this->refreshAccessToken($googleAccount);
    }

    /**
     * Refresh the access token using the refresh token.
     */
    public function refreshAccessToken(GoogleAccount $googleAccount): ?string
    {
        try {
            $response = Http::asForm()->post(config('google.token_url'), [
                'client_id' => config('google.client_id'),
                'client_secret' => config('google.client_secret'),
                'refresh_token' => $googleAccount->refresh_token,
                'grant_type' => 'refresh_token',
            ]);

            if (!$response->successful()) {
                Log::error('Failed to refresh Google token', [
                    'user_id' => $googleAccount->user_id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            // Update the stored tokens
            $googleAccount->update([
                'access_token' => $data['access_token'],
                'expires_at' => now()->addSeconds($data['expires_in'] ?? 3600),
            ]);

            Log::info('Google token refreshed successfully', [
                'user_id' => $googleAccount->user_id,
            ]);

            return $data['access_token'];
        } catch (\Exception $e) {
            Log::error('Exception while refreshing Google token', [
                'user_id' => $googleAccount->user_id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Exchange authorization code for tokens.
     */
    public function exchangeCodeForTokens(string $code): ?array
    {
        try {
            $response = Http::asForm()->post(config('google.token_url'), [
                'client_id' => config('google.client_id'),
                'client_secret' => config('google.client_secret'),
                'code' => $code,
                'redirect_uri' => config('google.redirect_uri'),
                'grant_type' => 'authorization_code',
            ]);

            if (!$response->successful()) {
                Log::error('Failed to exchange Google code for tokens', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception while exchanging Google code', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get user info from Google.
     */
    public function getUserInfo(string $accessToken): ?array
    {
        try {
            $response = Http::withToken($accessToken)
                ->get(config('google.userinfo_url'));

            if (!$response->successful()) {
                Log::error('Failed to get Google user info', [
                    'status' => $response->status(),
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception while getting Google user info', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Revoke the access token.
     */
    public function revokeToken(GoogleAccount $googleAccount): bool
    {
        try {
            $token = $googleAccount->access_token ?? $googleAccount->refresh_token;

            if (!$token) {
                return true;
            }

            $response = Http::asForm()->post(config('google.revoke_url'), [
                'token' => $token,
            ]);

            // Even if revocation fails, we'll delete local tokens
            if (!$response->successful()) {
                Log::warning('Failed to revoke Google token (might already be revoked)', [
                    'user_id' => $googleAccount->user_id,
                    'status' => $response->status(),
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Exception while revoking Google token', [
                'user_id' => $googleAccount->user_id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Build the OAuth authorization URL.
     */
    public function getAuthorizationUrl(string $state = null): string
    {
        $params = [
            'client_id' => config('google.client_id'),
            'redirect_uri' => config('google.redirect_uri'),
            'response_type' => 'code',
            'scope' => implode(' ', config('google.scopes')),
            'access_type' => 'offline',
            'prompt' => 'consent',
        ];

        if ($state) {
            $params['state'] = $state;
        }

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }
}
