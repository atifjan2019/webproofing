<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\Google\GoogleAnalyticsService;
use App\Services\Google\GoogleSearchConsoleService;
use App\Services\Google\GoogleTokenManager;
use App\Services\TrialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteGoogleController extends Controller
{
    protected GoogleAnalyticsService $ga4Service;
    protected GoogleSearchConsoleService $gscService;
    protected TrialService $trialService;

    public function __construct(
        GoogleAnalyticsService $ga4Service,
        GoogleSearchConsoleService $gscService,
        TrialService $trialService
    ) {
        $this->middleware('auth');
        $this->ga4Service = $ga4Service;
        $this->gscService = $gscService;
        $this->trialService = $trialService;
    }

    /**
     * Show the Google services page for a site.
     */
    public function show(Site $site)
    {
        // Ensure user owns the site
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $googleAccount = $user->googleAccount;

        // Get trial status from centralized service
        $trialStatus = $this->trialService->getSiteStatus($site);

        return view('sites.google-connect', [
            'site' => $site,
            'trialStatus' => $trialStatus,
            'googleAccount' => $googleAccount,
            'hasGoogleAccount' => $googleAccount !== null,
            'hasRefreshToken' => $googleAccount?->hasRefreshToken() ?? false,
        ]);
    }

    /**
     * Show the Google configuration page for selecting properties.
     */
    public function configure(Site $site)
    {
        // Ensure user owns the site
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $googleAccount = $user->googleAccount;

        if (!$googleAccount) {
            return redirect()->route('sites.google', $site)
                ->with('error', 'Please connect your Google account first.');
        }

        // Fetch available properties
        $ga4Properties = $this->ga4Service->listProperties($user) ?? [];
        $gscProperties = $this->gscService->listProperties($user) ?? [];

        $trialStatus = $this->trialService->getSiteStatus($site);

        return view('sites.google-configure', [
            'site' => $site,
            'trialStatus' => $trialStatus,
            'googleAccount' => $googleAccount,
            'ga4Properties' => $ga4Properties,
            'gscProperties' => $gscProperties,
        ]);
    }

    /**
     * Store the selected Google properties for a site.
     */
    public function store(Request $request, Site $site)
    {
        // Ensure user owns the site
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'ga4_property_id' => 'nullable|string',
            'ga4_property_name' => 'nullable|string',
            'gsc_site_url' => 'nullable|string',
        ]);

        $site->update($validated);

        return redirect()->route('sites.google', $site)
            ->with('success', 'Google services configured successfully.');
    }
    /**
     * Refresh Google connection data.
     */
    public function refresh(Site $site, GoogleTokenManager $tokenManager)
    {
        // Ensure user owns the site
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        if ($user->googleAccount) {
            // Force token refresh to ensure connection is valid
            $tokenManager->refreshAccessToken($user->googleAccount);

            // Note: Properties are fetched live in the configure page, so we don't need to clear any cache here.
            // But confirming the token works is a good step.
        }

        return redirect()->route('sites.google', $site)
            ->with('success', 'Google connection data refreshed.');
    }
}
