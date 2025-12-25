<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteScreenshot;
use App\Services\TrialService;
use App\Services\ScreenshotService;
use Illuminate\Http\Request;

class ScreenshotController extends Controller
{
    protected TrialService $trialService;
    protected ScreenshotService $screenshotService;

    public function __construct(TrialService $trialService, ScreenshotService $screenshotService)
    {
        $this->trialService = $trialService;
        $this->screenshotService = $screenshotService;
    }

    /**
     * Display screenshots for a site.
     */
    public function index(Site $site)
    {
        $this->authorize('view', $site);

        $screenshots = $site->screenshots()
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        $trialStatus = $this->trialService->getSiteStatus($site);

        return view('sites.screenshots', compact('site', 'screenshots', 'trialStatus'));
    }

    /**
     * Take a new screenshot.
     */
    public function capture(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $trialStatus = $this->trialService->getSiteStatus($site);

        if (!$trialStatus['can_monitor']) {
            return back()->with('error', 'Monitoring is paused. Please upgrade to take screenshots.');
        }

        $result = $this->screenshotService->captureAndSave($site, $request->input('device_type', 'desktop'));

        if ($result['success']) {
            return back()->with('success', 'Screenshot captured successfully!');
        } else {
            return back()->with('error', 'Screenshot failed: ' . $result['error']);
        }
    }

    /**
     * Delete a screenshot.
     */
    public function destroy(Site $site, SiteScreenshot $screenshot)
    {
        $this->authorize('update', $site);

        if ($screenshot->site_id !== $site->id) {
            abort(404);
        }

        $screenshot->delete();

        return back()->with('success', 'Screenshot deleted.');
    }
}
