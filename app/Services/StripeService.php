<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Get or create a Stripe customer for the user.
     */
    public function getOrCreateCustomer(User $user): Customer
    {
        if ($user->stripe_customer_id) {
            return Customer::retrieve($user->stripe_customer_id);
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    /**
     * Create a Stripe Checkout session for subscription.
     */
    public function createCheckoutSession(User $user, int $quantity = 1): Session
    {
        $customer = $this->getOrCreateCustomer($user);

        // Check if user already has a subscription
        $existingSubscription = $user->subscription;

        $sessionParams = [
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => config('stripe.price_id'),
                    'quantity' => $quantity,
                ]
            ],
            'mode' => 'subscription',
            'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.cancel'),
            'metadata' => [
                'user_id' => $user->id,
            ],
            'allow_promotion_codes' => true,
        ];

        // Add trial period if user doesn't have an existing subscription
        if (!$existingSubscription) {
            $sessionParams['subscription_data'] = [
                'trial_period_days' => config('stripe.trial_days'),
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ];
        }

        return Session::create($sessionParams);
    }

    /**
     * Update subscription quantity in Stripe.
     */
    public function updateSubscriptionQuantity(Subscription $subscription, int $newQuantity): StripeSubscription
    {
        $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_subscription_id);

        // Get the subscription item ID
        $subscriptionItemId = $stripeSubscription->items->data[0]->id;

        return StripeSubscription::update($subscription->stripe_subscription_id, [
            'items' => [
                [
                    'id' => $subscriptionItemId,
                    'quantity' => $newQuantity,
                ]
            ],
            'proration_behavior' => 'create_prorations',
        ]);
    }

    /**
     * Cancel a subscription.
     */
    public function cancelSubscription(Subscription $subscription): StripeSubscription
    {
        return StripeSubscription::update($subscription->stripe_subscription_id, [
            'cancel_at_period_end' => true,
        ]);
    }

    /**
     * Resume a canceled subscription.
     */
    public function resumeSubscription(Subscription $subscription): StripeSubscription
    {
        return StripeSubscription::update($subscription->stripe_subscription_id, [
            'cancel_at_period_end' => false,
        ]);
    }

    /**
     * Get subscription from Stripe.
     */
    public function getSubscription(string $subscriptionId): StripeSubscription
    {
        return StripeSubscription::retrieve($subscriptionId);
    }

    /**
     * Sync subscription data from Stripe to local database.
     */
    public function syncSubscription(StripeSubscription $stripeSubscription, User $user): Subscription
    {
        $data = [
            'user_id' => $user->id,
            'stripe_subscription_id' => $stripeSubscription->id,
            'status' => $stripeSubscription->status,
            'quantity' => $stripeSubscription->items->data[0]->quantity ?? 1,
            'trial_ends_at' => $stripeSubscription->trial_end
                ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end)
                : null,
            'current_period_ends_at' => $stripeSubscription->current_period_end
                ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                : null,
        ];

        return Subscription::updateOrCreate(
            ['stripe_subscription_id' => $stripeSubscription->id],
            $data
        );
    }
}
