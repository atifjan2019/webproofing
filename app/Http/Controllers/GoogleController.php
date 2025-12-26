<?php

namespace App\Http\Controllers;

use App\Models\GoogleAccount;
use App\Services\Google\GoogleTokenManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    protected GoogleTokenManager $tokenManager;

    public function __construct(GoogleTokenManager $tokenManager)
    {
        $this->middleware('auth');
        $this->tokenManager = $tokenManager;
    }

    /**
     * Redirect user to Google consent screen.
     * GET /google/connect
     */
    public function connect(Request $request)
    {
        // Generate a state token for CSRF protection
        $state = Str::random(40);
        session(['google_oauth_state' => $state]);

        // Store the intended redirect URL (where to go after connecting)
        if ($request->has('redirect_to')) {
            session(['google_oauth_redirect' => $request->input('redirect_to')]);
        } elseif ($request->headers->has('referer')) {
            // Use the referer URL if no explicit redirect specified
            session(['google_oauth_redirect' => $request->headers->get('referer')]);
        }

        $authUrl = $this->tokenManager->getAuthorizationUrl($state);

        return redirect()->away($authUrl);
    }

    /**
     * Handle Google OAuth callback.
     * GET /google/callback
     */
    public function callback(Request $request)
    {
        // Validate state
        $storedState = session('google_oauth_state');
        $returnedState = $request->input('state');

        // Get stored redirect URL before clearing session
        $redirectUrl = session('google_oauth_redirect');

        if (!$storedState || $storedState !== $returnedState) {
            Log::warning('Google OAuth state mismatch', [
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('sites.index')
                ->with('error', 'Authentication failed. Please try again.');
        }

        // Clear state from session
        session()->forget('google_oauth_state');
        session()->forget('google_oauth_redirect');

        // Check for error
        if ($request->has('error')) {
            Log::warning('Google OAuth error', [
                'user_id' => Auth::id(),
                'error' => $request->input('error'),
            ]);
            $errorRedirect = $redirectUrl ? redirect($redirectUrl) : redirect()->route('sites.index');
            return $errorRedirect->with('error', 'Google authentication was cancelled or failed.');
        }

        // Get authorization code
        $code = $request->input('code');

        if (!$code) {
            $errorRedirect = $redirectUrl ? redirect($redirectUrl) : redirect()->route('sites.index');
            return $errorRedirect->with('error', 'No authorization code received from Google.');
        }

        // Exchange code for tokens
        $tokens = $this->tokenManager->exchangeCodeForTokens($code);

        if (!$tokens) {
            $errorRedirect = $redirectUrl ? redirect($redirectUrl) : redirect()->route('sites.index');
            return $errorRedirect->with('error', 'Failed to get tokens from Google. Please try again.');
        }

        // Get user info from Google (optional - we can still proceed without it)
        $userInfo = $this->tokenManager->getUserInfo($tokens['access_token']);

        // Save or update Google account
        $user = Auth::user();

        GoogleAccount::updateOrCreate(
            ['user_id' => $user->id],
            [
                'google_user_id' => $userInfo['id'] ?? null,
                'email' => $userInfo['email'] ?? $user->email, // Fallback to user's email
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? null,
                'expires_at' => now()->addSeconds($tokens['expires_in'] ?? 3600),
            ]
        );

        Log::info('Google account connected', [
            'user_id' => $user->id,
            'google_email' => $userInfo['email'] ?? 'unknown',
            'has_refresh_token' => !empty($tokens['refresh_token']),
        ]);

        $message = 'Google account connected successfully!';
        if (!$userInfo) {
            $message .= ' (Note: Could not fetch Google profile info)';
        }
        if (empty($tokens['refresh_token'])) {
            $message .= ' Warning: No refresh token received. You may need to reconnect later.';
        }

        // Redirect back to original page or sites index
        $successRedirect = $redirectUrl ? redirect($redirectUrl) : redirect()->route('sites.index');
        return $successRedirect->with('success', $message);
    }

    /**
     * Disconnect Google account.
     * POST /google/disconnect
     */
    public function disconnect(Request $request)
    {
        $user = Auth::user();
        $googleAccount = $user->googleAccount;

        if (!$googleAccount) {
            return redirect()->back()
                ->with('error', 'No Google account connected.');
        }

        // Revoke tokens at Google
        $this->tokenManager->revokeToken($googleAccount);

        // Delete local record
        $googleAccount->delete();

        // Clear GA4/GSC selections from all user's sites
        $user->sites()->update([
            'ga4_property_id' => null,
            'ga4_property_name' => null,
            'gsc_site_url' => null,
        ]);

        Log::info('Google account disconnected', [
            'user_id' => $user->id,
        ]);

        return redirect()->back()
            ->with('success', 'Google account disconnected successfully.');
    }

    /**
     * Get connection status and account info.
     */
    public function status()
    {
        $user = Auth::user();
        $googleAccount = $user->googleAccount;

        if (!$googleAccount) {
            return response()->json([
                'connected' => false,
            ]);
        }

        return response()->json([
            'connected' => true,
            'email' => $googleAccount->email,
            'has_refresh_token' => $googleAccount->hasRefreshToken(),
            'token_expired' => $googleAccount->isTokenExpired(),
        ]);
    }
}
