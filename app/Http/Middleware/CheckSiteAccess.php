<?php

namespace App\Http\Middleware;

use App\Services\TrialService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteAccess
{
    protected TrialService $trialService;

    public function __construct(TrialService $trialService)
    {
        $this->trialService = $trialService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $action = 'view'): Response
    {
        $site = $request->route('site');

        if (!$site) {
            return $next($request);
        }

        // Check ownership
        if ($site->user_id !== $request->user()->id) {
            abort(403, 'You do not have access to this site.');
        }

        // For write actions, check if site can be modified
        if (in_array($action, ['write', 'modify', 'update'])) {
            $trialStatus = $this->trialService->getSiteStatus($site);

            if (!$trialStatus['can_monitor']) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Site is paused',
                        'message' => $trialStatus['message'],
                        'upgrade_required' => true,
                    ], 403);
                }

                return redirect()->route('sites.show', $site)
                    ->with('error', 'This site is paused. ' . $trialStatus['message']);
            }
        }

        return $next($request);
    }
}
