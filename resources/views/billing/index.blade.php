<x-app-layout>
    <style>
        /* Billing Page Premium Styles */
        .billing-hero {
            position: relative;
            padding: 3rem 0;
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);
            border-radius: var(--radius-2xl);
            margin-bottom: var(--spacing-xl);
            overflow: hidden;
        }

        .billing-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(238, 49, 79, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(34, 139, 230, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .billing-hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 0 2rem;
        }

        .billing-hero h1 {
            font-size: 2.75rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .billing-hero p {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Status Badge */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 1.5rem;
            backdrop-filter: blur(8px);
        }

        .status-pill.active {
            background: rgba(18, 184, 134, 0.2);
            color: #34d399;
            border: 1px solid rgba(18, 184, 134, 0.3);
        }

        .status-pill.inactive {
            background: rgba(250, 176, 5, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(250, 176, 5, 0.3);
        }

        .status-pill.trial {
            background: rgba(34, 139, 230, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(34, 139, 230, 0.3);
        }

        .status-pill svg {
            width: 18px;
            height: 18px;
        }

        /* Pricing Card */
        .pricing-card-wrapper {
            display: flex;
            justify-content: center;
            margin-top: -4rem;
            position: relative;
            z-index: 10;
        }

        .pricing-card-premium {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.25),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .pricing-card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 35px 60px -15px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(0, 0, 0, 0.05);
        }

        .pricing-card-header {
            text-align: center;
            padding: 2.5rem 2rem 2rem;
            background: linear-gradient(180deg, rgba(238, 49, 79, 0.03) 0%, transparent 100%);
        }

        .pricing-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 9999px;
            margin-bottom: 1.5rem;
        }

        .pricing-badge svg {
            width: 14px;
            height: 14px;
        }

        .price-display {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .price-currency {
            font-size: 1.5rem;
            font-weight: 700;
            color: #000000;
        }

        .price-amount {
            font-size: 4.5rem;
            font-weight: 800;
            color: #000000;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .price-period {
            font-size: 1rem;
            color: var(--color-text-muted);
            font-weight: 500;
        }

        .price-per-site {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-top: 0.25rem;
        }

        .pricing-card-body {
            padding: 1.5rem 2rem 2rem;
        }

        /* Features List */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            background: var(--color-bg-secondary);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: var(--color-bg-tertiary);
            transform: translateX(4px);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000000;
            color: #ffffff;
            border-radius: 0.5rem;
            flex-shrink: 0;
        }

        .feature-icon svg {
            width: 18px;
            height: 18px;
        }

        .feature-text {
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--color-text-primary);
        }

        /* Subscribe Button */
        .subscribe-btn {
            width: 100%;
            padding: 1.125rem 2rem;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }

        .subscribe-btn:hover {
            background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .subscribe-btn:active {
            transform: translateY(0);
        }

        .subscribe-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .subscribe-btn svg {
            width: 20px;
            height: 20px;
        }

        .trial-note {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.8125rem;
            color: var(--color-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .trial-note svg {
            width: 16px;
            height: 16px;
            color: var(--color-success);
        }

        /* Subscription Info Cards */
        .subscription-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-top: 2.5rem;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            border-color: transparent;
            box-shadow: var(--shadow-lg);
        }

        .info-card-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-bg-secondary);
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .info-card-icon svg {
            width: 24px;
            height: 24px;
            color: var(--color-text-primary);
        }

        .info-card-label {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
            margin-bottom: 0.375rem;
        }

        .info-card-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .info-card-value span {
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--color-text-muted);
        }

        /* Security Badge */
        .security-badges {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--color-border);
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: var(--color-text-muted);
        }

        .security-badge svg {
            width: 18px;
            height: 18px;
            opacity: 0.7;
        }

        /* Manage Button */
        .manage-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: transparent;
            color: var(--color-text-primary);
            font-size: 0.9375rem;
            font-weight: 600;
            border: 2px solid var(--color-border);
            border-radius: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .manage-btn:hover {
            border-color: var(--color-text-primary);
            background: var(--color-bg-secondary);
        }

        .manage-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.1) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
    </style>

    <div class="container">
        <!-- Hero Section -->
        <div class="billing-hero animate-fadeInUp">
            <div class="billing-hero-content">
                <h1>Billing & Subscription</h1>
                <p>Manage your plan and payment details</p>
                
                @if($subscriptionStatus['has_subscription'])
                    <div class="status-pill {{ $subscriptionStatus['is_active'] ? ($subscriptionStatus['status'] === 'trialing' ? 'trial' : 'active') : 'inactive' }}">
                        @if($subscriptionStatus['is_active'])
                            @if($subscriptionStatus['status'] === 'trialing')
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Free Trial Active
                            @else
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Subscription Active
                            @endif
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $subscriptionStatus['label'] }}
                        @endif
                    </div>
                @else
                    <div class="status-pill inactive">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        No Subscription
                    </div>
                @endif
            </div>
        </div>

        <!-- Pricing Card -->
        <div class="pricing-card-wrapper animate-fadeInUp" style="animation-delay: 100ms;">
            <div class="pricing-card-premium">
                <div class="pricing-card-header">
                    <div class="pricing-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        Pro Plan
                    </div>
                    
                    <div class="price-display">
                        <span class="price-currency">$</span>
                        <span class="price-amount">9</span>
                        <span class="price-currency">.99</span>
                    </div>
                    <p class="price-period">per month</p>
                    <p class="price-per-site">per monitored website</p>
                </div>
                
                <div class="pricing-card-body">
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="feature-text">Daily Automated Screenshots</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="feature-text">Google Analytics Integration</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <span class="feature-text">Search Console Data</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="feature-text">PageSpeed Insights</span>
                        </div>
                    </div>

                    @if(!$subscriptionStatus['has_subscription'])
                        <button id="checkout-btn" class="subscribe-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Start Free Trial
                        </button>
                        <p class="trial-note">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            7-day free trial • Cancel anytime
                        </p>
                    @else
                        <button class="manage-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Manage Subscription
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if($subscriptionStatus['has_subscription'])
            <!-- Subscription Info Cards -->
            <div class="subscription-info-grid animate-fadeInUp" style="animation-delay: 200ms;">
                <div class="info-card">
                    <div class="info-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <p class="info-card-label">Websites Covered</p>
                    <p class="info-card-value">{{ $subscriptionStatus['quantity'] }} <span>sites</span></p>
                </div>

                @if(isset($subscriptionStatus['current_period_ends_at']))
                    <div class="info-card">
                        <div class="info-card-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="info-card-label">Next Billing Date</p>
                        <p class="info-card-value">{{ $subscriptionStatus['current_period_ends_at']->format('M d, Y') }}</p>
                    </div>
                @endif

                <div class="info-card">
                    <div class="info-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="info-card-label">Monthly Total</p>
                    <p class="info-card-value">${{ number_format($subscriptionStatus['quantity'] * 9.99, 2) }}</p>
                </div>
            </div>
        @endif

        <!-- Security Badges -->
        <div class="security-badges animate-fadeInUp" style="animation-delay: 300ms;">
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                SSL Secured
            </div>
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Stripe Payments
            </div>
            <div class="security-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Cancel Anytime
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkoutBtn = document.getElementById('checkout-btn');

                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', async function () {
                        checkoutBtn.disabled = true;
                        checkoutBtn.innerHTML = `
                            <svg class="animate-spin" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="31.416" stroke-dashoffset="10"></circle>
                            </svg>
                            Redirecting...
                        `;

                        try {
                            const response = await fetch('{{ route("billing.checkout") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                            });

                            const data = await response.json();

                            if (data.checkout_url) {
                                window.location.href = data.checkout_url;
                            } else if (data.error) {
                                alert('Error: ' + data.error);
                                resetButton();
                            }
                        } catch (error) {
                            alert('An error occurred. Please try again.');
                            resetButton();
                        }

                        function resetButton() {
                            checkoutBtn.disabled = false;
                            checkoutBtn.innerHTML = `
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Start Free Trial
                            `;
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>