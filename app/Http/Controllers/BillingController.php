<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class BillingController extends Controller
{
    protected StripeService $stripeService;
    protected SubscriptionService $subscriptionService;

    public function __construct(StripeService $stripeService, SubscriptionService $subscriptionService)
    {
        $this->stripeService = $stripeService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Create a Stripe Checkout session and redirect.
     * POST /billing/checkout
     */
    public function checkout(Request $request)
    {
        $user = $request->user();

        // Calculate quantity based on user's websites
        $quantity = $this->subscriptionService->getRequiredQuantity($user);

        try {
            $session = $this->stripeService->createCheckoutSession($user, $quantity);

            if ($request->wantsJson()) {
                return response()->json([
                    'checkout_url' => $session->url,
                ]);
            }

            return redirect($session->url);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to create checkout session: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to initiate checkout: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment return.
     * GET /billing/success
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid checkout session.');
        }

        try {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid' || $session->status === 'complete') {
                // Get and sync the subscription
                $stripeSubscription = $this->stripeService->getSubscription($session->subscription);
                $this->stripeService->syncSubscription($stripeSubscription, $request->user());

                return redirect()->route('dashboard')->with('success', 'Subscription activated! Thank you for subscribing.');
            }

            return redirect()->route('dashboard')->with('info', 'Payment is being processed. Your subscription will be activated shortly.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to verify payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle checkout cancellation.
     * GET /billing/cancel
     */
    public function cancel(Request $request)
    {
        return redirect()->route('dashboard')->with('info', 'Checkout was canceled. You can try again anytime.');
    }

    /**
     * Display billing management page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $subscriptionStatus = $this->subscriptionService->getSubscriptionStatus($user);

        return view('billing.index', compact('subscriptionStatus'));
    }
}
