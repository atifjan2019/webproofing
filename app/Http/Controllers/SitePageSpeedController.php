<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\TrialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SitePageSpeedController extends Controller
{
    protected TrialService $trialService;

    public function __construct(TrialService $trialService)
    {
        $this->trialService = $trialService;
    }

    public function show(Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $trialStatus = $this->trialService->getSiteStatus($site);

        // Fetch latest metrics
        $metrics = $site->pagespeedMetrics()
            ->get()
            ->keyBy('strategy');

        return view('sites.pagespeed', [
            'site' => $site,
            'metrics' => $metrics,
            'trialStatus' => $trialStatus,
        ]);
    }

    public function analyze(Request $request, Site $site, \App\Services\Google\PageSpeedService $pageSpeedService)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $trialStatus = $this->trialService->getSiteStatus($site);

        if (!Auth::user()->service_speed_test) {
            return response()->json([
                'error' => 'Speed test service has been disabled for your account.',
            ], 403);
        }

        if (!$trialStatus['can_monitor']) {
            return response()->json([
                'error' => 'Plan upgrade required to run speed tests.',
            ], 403);
        }

        $request->validate([
            'strategy' => 'in:mobile,desktop',
        ]);

        $strategy = $request->input('strategy', 'mobile');

        $url = $site->domain;
        if (!str_starts_with($url, 'http')) {
            $url = 'https://' . $url;
        }

        // Run analysis
        $result = $pageSpeedService->analyze($url, $strategy);

        if (!$result) {
            return response()->json([
                'error' => 'Failed to analyze metrics. Please check your API key and try again.',
            ], 500);
        }

        // Store result
        $site->pagespeedMetrics()->updateOrCreate(
            ['strategy' => $strategy],
            [
                'performance_score' => $result['scores']['performance'] ?? null,
                'seo_score' => $result['scores']['seo'] ?? null,
                'accessibility_score' => $result['scores']['accessibility'] ?? null,
                'best_practices_score' => $result['scores']['best_practices'] ?? null,
                'lcp' => $result['metrics']['lcp'] ?? null,
                'fcp' => $result['metrics']['fcp'] ?? null,
                'cls' => $result['metrics']['cls'] ?? null,
                'tbt' => $result['metrics']['tbt'] ?? null,
                'speed_index' => $result['metrics']['speed_index'] ?? null,
            ]
        );

        return response()->json($result);
    }
}
