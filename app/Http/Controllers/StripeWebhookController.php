<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    protected StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle Stripe webhook events.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = config('stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook: Invalid signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Stripe webhook received', ['type' => $event->type]);

        switch ($event->type) {
            case 'invoice.paid':
                $this->handleInvoicePaid($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            default:
                Log::info('Stripe webhook: Unhandled event type', ['type' => $event->type]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle invoice.paid event.
     * Called when payment succeeds and invoice is paid.
     */
    protected function handleInvoicePaid($invoice): void
    {
        if (!$invoice->subscription) {
            return;
        }

        Log::info('Invoice paid', [
            'subscription_id' => $invoice->subscription,
            'amount_paid' => $invoice->amount_paid,
        ]);

        // Get the subscription and update status to active
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $stripeSubscription = $this->stripeService->getSubscription($invoice->subscription);
            $this->stripeService->syncSubscription($stripeSubscription, $subscription->user);
        }
    }

    /**
     * Handle invoice.payment_failed event.
     * Called when payment fails.
     */
    protected function handleInvoicePaymentFailed($invoice): void
    {
        if (!$invoice->subscription) {
            return;
        }

        Log::warning('Invoice payment failed', [
            'subscription_id' => $invoice->subscription,
            'customer' => $invoice->customer,
        ]);

        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            // Update to past_due status
            $subscription->update([
                'status' => 'past_due',
            ]);

            // TODO: Send email notification to user about payment failure
        }
    }

    /**
     * Handle customer.subscription.updated event.
     * Called when subscription changes (status, quantity, etc).
     */
    protected function handleSubscriptionUpdated($stripeSubscription): void
    {
        Log::info('Subscription updated', [
            'subscription_id' => $stripeSubscription->id,
            'status' => $stripeSubscription->status,
        ]);

        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $this->stripeService->syncSubscription($stripeSubscription, $subscription->user);
        } else {
            // Subscription not found locally, try to find user by customer ID
            $user = User::where('stripe_customer_id', $stripeSubscription->customer)->first();

            if ($user) {
                $this->stripeService->syncSubscription($stripeSubscription, $user);
            }
        }
    }

    /**
     * Handle customer.subscription.deleted event.
     * Called when subscription is canceled/deleted.
     */
    protected function handleSubscriptionDeleted($stripeSubscription): void
    {
        Log::info('Subscription deleted', [
            'subscription_id' => $stripeSubscription->id,
        ]);

        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'canceled',
            ]);
        }
    }

    /**
     * Handle checkout.session.completed event.
     * Called when checkout is completed (backup for success redirect).
     */
    protected function handleCheckoutCompleted($session): void
    {
        if ($session->mode !== 'subscription' || !$session->subscription) {
            return;
        }

        Log::info('Checkout completed', [
            'session_id' => $session->id,
            'subscription_id' => $session->subscription,
        ]);

        $user = User::where('stripe_customer_id', $session->customer)->first();

        if ($user) {
            $stripeSubscription = $this->stripeService->getSubscription($session->subscription);
            $this->stripeService->syncSubscription($stripeSubscription, $user);
        }
    }
}
