<?php

namespace App\Services;

use App\Models\Site;
use App\Models\TrialDomain;
use Carbon\Carbon;

class TrialService
{
    protected const TRIAL_DAYS = 7;

    /**
     * Start a trial for a site if eligible.
     * Returns the trial record or null if not eligible.
     */
    public function startTrial(Site $site): ?TrialDomain
    {
        // Check if trial already exists for this domain
        if ($this->hasTrialBeenUsed($site->domain)) {
            return null;
        }

        return TrialDomain::create([
            'domain' => $site->domain,
            'user_id' => $site->user_id,
            'site_id' => $site->id,
            'trial_started_at' => now(),
            'trial_ends_at' => now()->addDays(self::TRIAL_DAYS),
            'is_expired' => false,
        ]);
    }

    /**
     * Check if a domain has already used its trial globally.
     */
    public function hasTrialBeenUsed(string $domain): bool
    {
        return TrialDomain::where('domain', strtolower($domain))->exists();
    }

    /**
     * Check if a site is eligible for trial.
     */
    public function isEligibleForTrial(string $domain): bool
    {
        return !$this->hasTrialBeenUsed($domain);
    }

    /**
     * Get the trial record for a domain.
     */
    public function getTrialForDomain(string $domain): ?TrialDomain
    {
        return TrialDomain::where('domain', strtolower($domain))->first();
    }

    /**
     * Check if a trial is still active.
     */
    public function isTrialActive(TrialDomain $trial): bool
    {
        return !$trial->is_expired && now()->lt($trial->trial_ends_at);
    }

    /**
     * Get remaining trial days.
     */
    public function getRemainingDays(TrialDomain $trial): int
    {
        if ($trial->is_expired || now()->gte($trial->trial_ends_at)) {
            return 0;
        }

        return (int) now()->diffInDays($trial->trial_ends_at, false);
    }

    /**
     * Mark expired trials.
     */
    public function markExpiredTrials(): int
    {
        return TrialDomain::where('is_expired', false)
            ->where('trial_ends_at', '<', now())
            ->update(['is_expired' => true]);
    }

    /**
     * Get site status based on trial.
     */
    public function getSiteStatus(Site $site): array
    {
        $trial = $site->trialDomain;

        if (!$trial) {
            // Domain has already been used for trial by someone else
            $existingTrial = $this->getTrialForDomain($site->domain);

            if ($existingTrial) {
                return [
                    'status' => 'paused',
                    'label' => 'Upgrade Required',
                    'message' => 'This domain has already used its free trial.',
                    'can_monitor' => false,
                    'remaining_days' => 0,
                ];
            }

            return [
                'status' => 'unknown',
                'label' => 'No Trial',
                'message' => 'Trial not started.',
                'can_monitor' => false,
                'remaining_days' => 0,
            ];
        }

        if ($this->isTrialActive($trial)) {
            $remaining = $this->getRemainingDays($trial);
            return [
                'status' => 'trial',
                'label' => "Trial ({$remaining} days left)",
                'message' => "Your free trial ends " . $trial->trial_ends_at->format('M d, Y'),
                'can_monitor' => true,
                'remaining_days' => $remaining,
                'ends_at' => $trial->trial_ends_at,
            ];
        }

        // Trial expired
        return [
            'status' => 'expired',
            'label' => 'Trial Expired',
            'message' => 'Your free trial has ended. Upgrade to continue monitoring.',
            'can_monitor' => false,
            'remaining_days' => 0,
            'expired_at' => $trial->trial_ends_at,
        ];
    }

    /**
     * Check if site can perform monitoring actions.
     */
    public function canMonitor(Site $site): bool
    {
        $status = $this->getSiteStatus($site);
        return $status['can_monitor'];
    }
}
