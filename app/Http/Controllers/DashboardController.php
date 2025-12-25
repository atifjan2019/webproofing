<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\TrialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected TrialService $trialService;

    public function __construct(TrialService $trialService)
    {
        $this->trialService = $trialService;
    }

    /**
     * Show the main dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        $sites = $user->sites()
            ->with('trialDomain')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($site) {
                $site->trial_status = $this->trialService->getSiteStatus($site);
                return $site;
            });

        // Calculate stats
        $stats = [
            'total' => $sites->count(),
            'active' => $sites->filter(fn($s) => $s->trial_status['can_monitor'])->count(),
            'paused' => $sites->filter(fn($s) => !$s->trial_status['can_monitor'])->count(),
            'on_trial' => $sites->filter(fn($s) => $s->trial_status['status'] === 'trial')->count(),
            'expired' => $sites->filter(fn($s) => $s->trial_status['status'] === 'expired')->count(),
        ];

        // Sites expiring soon (within 3 days)
        $expiringSoon = $sites->filter(function ($site) {
            return $site->trial_status['status'] === 'trial'
                && $site->trial_status['remaining_days'] <= 3;
        });

        // Recent sites
        $recentSites = $sites->take(5);

        return view('dashboard', compact('stats', 'sites', 'expiringSoon', 'recentSites'));
    }
}
