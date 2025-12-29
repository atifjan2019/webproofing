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
        $user = $site->user;

        // If user has free access, they can add unlimited sites
        // We create a trial record but it acts differently in status check
        if ($user && $user->has_free_access) {
            return TrialDomain::create([
                'domain' => $site->domain,
                'user_id' => $site->user_id,
                'site_id' => $site->id,
                'trial_started_at' => now(),
                'trial_ends_at' => now()->addYears(10), // Effectively forever (within timestamp limits)
                'is_expired' => false,
            ]);
        }

        // Check if trial already exists for this domain globally
        if ($this->hasTrialBeenUsed($site->domain)) {
            return null;
        }

        // Check if USER has already used their 1 free trial allowance
        if ($this->hasUserUsedTrialAllowance($user)) {
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
     * Check if user has already used their trial allowance (1 site).
     */
    public function hasUserUsedTrialAllowance($user): bool
    {
        if (!$user)
            return false;
        return TrialDomain::where('user_id', $user->id)->exists();
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
     * Takes user into account for allowance check.
     */
    public function isEligibleForTrial(string $domain, $user = null): bool
    {
        // 1. Domain check
        if ($this->hasTrialBeenUsed($domain)) {
            return false;
        }

        // 2. User check
        if ($user) {
            if ($user->has_free_access) {
                return true;
            }
            if ($this->hasUserUsedTrialAllowance($user)) {
                return false;
            }
        }

        return true;
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
     * Get site status based on trial and subscription.
     */
    public function getSiteStatus(Site $site): array
    {
        $user = $site->user;

        // Special override for Free Access / Super Admin
        if ($user && $user->has_free_access) {
            return [
                'status' => 'active_free',
                'label' => 'Active Free Account',
                'message' => 'You have free access to all features.',
                'can_monitor' => true,
                'remaining_days' => null,
                'has_subscription' => true, // Treated as subscribed
            ];
        }

        // First, check if user has an active subscription
        if ($user && $user->hasActiveSubscription()) {
            $subscription = $user->subscription;

            if ($subscription->onTrial()) {
                $trialEnds = $subscription->trial_ends_at;
                $remainingDays = (int) now()->diffInDays($trialEnds, false);

                return [
                    'status' => 'subscribed_trial',
                    'label' => "Subscribed (Trial - {$remainingDays} days left)",
                    'message' => "Your subscription trial ends " . $trialEnds->format('M d, Y'),
                    'can_monitor' => true,
                    'remaining_days' => max(0, $remainingDays),
                    'ends_at' => $trialEnds,
                    'has_subscription' => true,
                ];
            }

            return [
                'status' => 'subscribed',
                'label' => 'Subscribed',
                'message' => 'Your subscription is active.',
                'can_monitor' => true,
                'remaining_days' => null,
                'has_subscription' => true,
            ];
        }

        // Fall back to domain-based trial logic
        $trial = $site->trialDomain;

        if (!$trial) {
            // Check why no trial?
            // Case 1: Domain used elsewhere
            $existingTrial = $this->getTrialForDomain($site->domain);
            if ($existingTrial) {
                return [
                    'status' => 'paused',
                    'label' => 'Upgrade Required',
                    'message' => 'This domain has already used its free trial.',
                    'can_monitor' => false,
                    'remaining_days' => 0,
                    'has_subscription' => false,
                ];
            }

            // Case 2: User exhausted allowance?
            // If they are here with no trial record, but own the site, it implies they added it 
            // but were not eligible for trial at creation time.
            return [
                'status' => 'no_trial',
                'label' => 'No Trial', // Or "Limit Reached"
                'message' => 'Free trial limit reached (1 site per account). Upgrade to monitor more sites.',
                'can_monitor' => false,
                'remaining_days' => 0,
                'has_subscription' => false,
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
                'has_subscription' => false,
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
            'has_subscription' => false,
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
