<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\Google\GoogleAnalyticsService;
use App\Services\Google\GoogleSearchConsoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteGoogleController extends Controller
{
    protected GoogleAnalyticsService $ga4Service;
    protected GoogleSearchConsoleService $gscService;

    public function __construct(
        GoogleAnalyticsService $ga4Service,
        GoogleSearchConsoleService $gscService
    ) {
        $this->middleware('auth');
        $this->ga4Service = $ga4Service;
        $this->gscService = $gscService;
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

        // Get trial status
        $trialStatus = $this->getTrialStatus($site);

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

        $trialStatus = $this->getTrialStatus($site);

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
     * Get trial status for a site.
     */
    protected function getTrialStatus(Site $site): array
    {
        $trialDomain = $site->trialDomain;

        if (!$trialDomain) {
            return [
                'status' => 'none',
                'label' => 'No Trial',
                'can_monitor' => false,
                'message' => 'This domain requires an active subscription.',
            ];
        }

        if ($trialDomain->is_expired || now()->gte($trialDomain->trial_ends_at)) {
            return [
                'status' => 'expired',
                'label' => 'Trial Expired',
                'can_monitor' => false,
                'remaining_days' => 0,
                'message' => 'Your trial has expired. Upgrade to continue monitoring.',
            ];
        }

        $remainingDays = now()->diffInDays($trialDomain->trial_ends_at);

        return [
            'status' => 'trial',
            'label' => 'Trial Active',
            'can_monitor' => true,
            'remaining_days' => $remainingDays,
            'message' => "{$remainingDays} days remaining in your trial.",
        ];
    }
}
