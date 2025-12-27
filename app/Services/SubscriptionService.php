<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionService
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(User $user): bool
    {
        $subscription = $user->subscription;

        return $subscription && $subscription->isActive();
    }

    /**
     * Get user's subscription status info.
     */
    public function getSubscriptionStatus(User $user): array
    {
        $subscription = $user->subscription;

        if (!$subscription) {
            return [
                'has_subscription' => false,
                'status' => 'none',
                'label' => 'No Subscription',
                'message' => 'Subscribe to enable full features.',
                'is_active' => false,
                'quantity' => 0,
            ];
        }

        $isActive = $subscription->isActive();

        return [
            'has_subscription' => true,
            'status' => $subscription->status,
            'label' => $this->getStatusLabel($subscription),
            'message' => $this->getStatusMessage($subscription),
            'is_active' => $isActive,
            'quantity' => $subscription->quantity,
            'trial_ends_at' => $subscription->trial_ends_at,
            'current_period_ends_at' => $subscription->current_period_ends_at,
            'on_trial' => $subscription->onTrial(),
        ];
    }

    /**
     * Get human-readable status label.
     */
    protected function getStatusLabel(Subscription $subscription): string
    {
        return match ($subscription->status) {
            'active' => 'Active',
            'trialing' => 'Free Trial',
            'past_due' => 'Past Due',
            'canceled' => 'Canceled',
            'unpaid' => 'Unpaid',
            'incomplete' => 'Incomplete',
            default => ucfirst($subscription->status),
        };
    }

    /**
     * Get status message for display.
     */
    protected function getStatusMessage(Subscription $subscription): string
    {
        return match ($subscription->status) {
            'active' => 'Your subscription is active.',
            'trialing' => $subscription->trial_ends_at
            ? "Free trial ends " . $subscription->trial_ends_at->format('M d, Y')
            : 'You are on a free trial.',
            'past_due' => 'Payment failed. Please update your payment method.',
            'canceled' => 'Your subscription has been canceled.',
            'unpaid' => 'Payment is unpaid. Please update your payment method.',
            'incomplete' => 'Please complete your payment setup.',
            default => 'Please check your subscription status.',
        };
    }

    /**
     * Calculate required quantity based on user's websites.
     */
    public function getRequiredQuantity(User $user): int
    {
        return max(1, $user->sites()->count());
    }

    /**
     * Sync subscription quantity with number of websites.
     */
    public function syncQuantityWithWebsites(User $user): void
    {
        $subscription = $user->subscription;

        if (!$subscription || !$subscription->isActive()) {
            return;
        }

        $requiredQuantity = $this->getRequiredQuantity($user);

        if ($subscription->quantity !== $requiredQuantity) {
            $stripeSubscription = $this->stripeService->updateSubscriptionQuantity(
                $subscription,
                $requiredQuantity
            );

            $subscription->update([
                'quantity' => $requiredQuantity,
            ]);
        }
    }

    /**
     * Check if user can add more websites.
     */
    public function canAddWebsite(User $user): bool
    {
        // Allow adding websites - subscription quantity will be updated
        return true;
    }
}
