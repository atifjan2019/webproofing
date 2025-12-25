<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteGoogleProperty;
use App\Services\GoogleService;
use App\Services\TrialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleConnectController extends Controller
{
    protected TrialService $trialService;
    protected GoogleService $googleService;

    public function __construct(TrialService $trialService, GoogleService $googleService)
    {
        $this->trialService = $trialService;
        $this->googleService = $googleService;
    }

    /**
     * Show Google connection page for a site.
     */
    public function show(Site $site)
    {
        $this->authorize('view', $site);

        $site->load('googleProperty');
        $trialStatus = $this->trialService->getSiteStatus($site);
        $isConnected = $this->googleService->isConnected(Auth::user());
        $connectedEmail = $this->googleService->getConnectedEmail(Auth::user());

        return view('sites.google-connect', compact('site', 'trialStatus', 'isConnected', 'connectedEmail'));
    }

    /**
     * Initiate Google OAuth connection.
     */
    public function connect(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $trialStatus = $this->trialService->getSiteStatus($site);
        if (!$trialStatus['can_monitor']) {
            return back()->with('error', 'Please upgrade to connect Google services.');
        }

        // If already connected, just redirect to configure
        if ($this->googleService->isConnected(Auth::user())) {
            return redirect()->route('sites.google.configure', $site);
        }

        // Redirect to Google OAuth
        $url = $this->googleService->getAuthUrl($site->id);
        return redirect($url);
    }

    /**
     * Handle Google OAuth callback.
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('dashboard')->with('error', 'Google connection failed: ' . $request->error);
        }

        if (!$request->has('code')) {
            return redirect()->route('dashboard')->with('error', 'Invalid Google response.');
        }

        try {
            // Process callback
            $this->googleService->handleCallback($request->code, Auth::user());

            // Check if we have state (site_id) to redirect back to specific site
            if ($request->has('state')) {
                try {
                    $state = decrypt($request->state);
                    if (isset($state['site_id'])) {
                        $site = Site::find($state['site_id']);
                        if ($site && $request->user()->can('update', $site)) {
                            return redirect()->route('sites.google.configure', $site)
                                ->with('success', 'Google account connected! Now configure your properties.');
                        }
                    }
                } catch (\Exception $e) {
                    // Invalid state, ignore and redirect to dashboard/sites
                }
            }

            return redirect()->route('sites.index')
                ->with('success', 'Google account connected successfully!');

        } catch (\Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Failed to connect Google account: ' . $e->getMessage());
        }
    }

    /**
     * Show property configuration page.
     */
    public function configure(Site $site)
    {
        $this->authorize('update', $site);

        // Ensure user has connected Google account
        if (!$this->googleService->isConnected(Auth::user())) {
            return redirect()->route('sites.google', $site)
                ->with('error', 'Please connect your Google account first.');
        }

        $site->load('googleProperty');
        $trialStatus = $this->trialService->getSiteStatus($site);

        try {
            // Fetch real properties
            $availableGa4Properties = $this->googleService->getGA4Properties(Auth::user());
            $availableGscProperties = $this->googleService->getSearchConsoleProperties(Auth::user());

            // Filter GSC properties to only show domain properties that match this site (optional, or show all)
            // For now, let's show all available domain properties so user can pick
            // But highlight the one matching the current domain
            $gscDomainProperty = 'sc-domain:' . $site->domain;

            // Check if access exists for the current site domain
            $hasGscAccess = $this->googleService->hasGscAccess(Auth::user(), $site->domain);

        } catch (\Exception $e) {
            return redirect()->route('sites.google', $site)
                ->with('error', 'Failed to fetch Google properties: ' . $e->getMessage());
        }

        return view('sites.google-configure', compact(
            'site',
            'trialStatus',
            'availableGa4Properties',
            'availableGscProperties',
            'gscDomainProperty',
            'hasGscAccess'
        ));
    }

    /**
     * Save Google property configuration.
     */
    public function store(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $trialStatus = $this->trialService->getSiteStatus($site);
        if (!$trialStatus['can_monitor']) {
            return back()->with('error', 'Please upgrade to save Google properties.');
        }

        $validated = $request->validate([
            'ga4_property_id' => 'nullable|string|max:255',
            'ga4_property_name' => 'nullable|string|max:255',
            'gsc_domain' => ['nullable', 'string', 'max:255', 'regex:/^sc-domain:.+$/'],
        ], [
            'gsc_domain.regex' => 'GSC domain must be in format: sc-domain:example.com',
        ]);

        // Verify access if setting properties
        if (!empty($validated['ga4_property_id'])) {
            if (!$this->googleService->hasGa4Access(Auth::user(), $validated['ga4_property_id'])) {
                return back()->withErrors(['ga4_property_id' => 'You do not have access to this GA4 property.']);
            }
        }

        if (!empty($validated['gsc_domain'])) {
            // Extract domain from sc-domain:example.com
            $domain = str_replace('sc-domain:', '', $validated['gsc_domain']);
            if (!$this->googleService->hasGscAccess(Auth::user(), $domain)) {
                return back()->withErrors(['gsc_domain' => 'You do not have access to this Search Console property.']);
            }
        }

        SiteGoogleProperty::updateOrCreate(
            ['site_id' => $site->id],
            [
                'ga4_property_id' => $validated['ga4_property_id'],
                'ga4_property_name' => $validated['ga4_property_name'],
                'ga4_connected' => !empty($validated['ga4_property_id']),
                'gsc_domain' => $validated['gsc_domain'],
                'gsc_connected' => !empty($validated['gsc_domain']),
            ]
        );

        return redirect()->route('sites.show', $site)
            ->with('success', 'Google properties saved successfully!');
    }

    /**
     * Disconnect Google services.
     */
    public function disconnect(Site $site)
    {
        $this->authorize('update', $site);

        // Disconnect site properties
        if ($site->googleProperty) {
            $site->googleProperty->update([
                'ga4_property_id' => null,
                'ga4_property_name' => null,
                'ga4_connected' => false,
                'gsc_domain' => null,
                'gsc_connected' => false,
            ]);
        }

        // Note: We don't disconnect the Google account here, just the site properties.
        // If we wanted to disconnect the Google User Account entirely, we would need a separate action.

        return redirect()->route('sites.google', $site)
            ->with('success', 'Google services disconnected for this site.');
    }
}
