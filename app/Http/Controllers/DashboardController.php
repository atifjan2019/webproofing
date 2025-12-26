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
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all sites for stats calculation (not paginated)
        $allSites = $user->sites()
            ->with('trialDomain')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($site) {
                $site->trial_status = $this->trialService->getSiteStatus($site);
                return $site;
            });

        // Calculate stats from all sites
        $stats = [
            'total' => $allSites->count(),
            'active' => $allSites->filter(fn($s) => $s->trial_status['can_monitor'])->count(),
            'paused' => $allSites->filter(fn($s) => !$s->trial_status['can_monitor'])->count(),
            'on_trial' => $allSites->filter(fn($s) => $s->trial_status['status'] === 'trial')->count(),
            'expired' => $allSites->filter(fn($s) => $s->trial_status['status'] === 'expired')->count(),
        ];

        // Sites expiring soon (within 3 days)
        $expiringSoon = $allSites->filter(function ($site) {
            return $site->trial_status['status'] === 'trial'
                && $site->trial_status['remaining_days'] <= 3;
        });

        // Paginated sites for display (6 per page)
        $paginatedSites = $user->sites()
            ->with('trialDomain')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        // Add trial status to paginated sites
        $paginatedSites->getCollection()->transform(function ($site) {
            $site->trial_status = $this->trialService->getSiteStatus($site);
            return $site;
        });

        // Recent sites (for quick access)
        $recentSites = $allSites->take(5);

        return view('dashboard', [
            'stats' => $stats,
            'sites' => $paginatedSites,
            'expiringSoon' => $expiringSoon,
            'recentSites' => $recentSites,
        ]);
    }
}
