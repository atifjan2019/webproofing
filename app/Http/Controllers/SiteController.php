<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Rules\UniqueDomainForUser;
use App\Rules\ValidDomain;
use App\Services\DomainService;
use App\Services\TrialService;
use App\Services\ScreenshotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    protected DomainService $domainService;
    protected TrialService $trialService;
    protected ScreenshotService $screenshotService;

    public function __construct(
        DomainService $domainService,
        TrialService $trialService,
        ScreenshotService $screenshotService
    ) {
        $this->domainService = $domainService;
        $this->trialService = $trialService;
        $this->screenshotService = $screenshotService;
    }
    /**
     * Display a listing of sites.
     */
    public function index()
    {
        $sites = Auth::user()->sites()
            ->with('trialDomain')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($site) {
                $site->trial_status = $this->trialService->getSiteStatus($site);
                return $site;
            });

        return view('sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new site.
     */
    public function create()
    {
        return view('sites.create');
    }
    /**
     * Store a newly created site.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'string', 'max:255', new ValidDomain(), new UniqueDomainForUser(Auth::id())],
        ]);

        $normalizedDomain = $this->domainService->normalize($request->url);

        $site = Site::create([
            'user_id' => Auth::id(),
            'raw_url' => $request->url,
            'domain' => $normalizedDomain,
            'name' => $normalizedDomain,
            'status' => 'active',
        ]);

        // Start trial if eligible
        if ($this->trialService->isEligibleForTrial($normalizedDomain, Auth::user())) {
            $this->trialService->startTrial($site);

            // Auto-capture screenshot in background
            \App\Jobs\CaptureSiteScreenshot::dispatch($site)->onConnection('database');

            // Auto-analyze speed in background
            \App\Jobs\AnalyzeSiteSpeed::dispatch($site)->onConnection('database');

            $message = "Site '{$normalizedDomain}' added successfully! We're capturing screenshots and analyzing performance in the background.";
        } else {
            $message = "Site '{$normalizedDomain}' added. This domain has already used its free trial - upgrade required to enable monitoring.";
        }

        return redirect()->route('sites.show', $site)->with('success', $message);
    }

    /**
     * Display the specified site.
     */
    public function show(Site $site)
    {
        $this->authorize('view', $site);

        $site->load([
            'trialDomain',
            'screenshots' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }
        ]);

        $trialStatus = $this->trialService->getSiteStatus($site);

        return view('sites.show', compact('site', 'trialStatus'));
    }

    /**
     * Remove the specified site.
     */
    public function destroy(Site $site)
    {
        $this->authorize('delete', $site);

        $domain = $site->domain;
        $site->delete();

        return redirect()->route('sites.index')->with('success', "Site '{$domain}' has been removed.");
    }
}
