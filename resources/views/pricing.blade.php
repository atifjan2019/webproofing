<x-app-layout>
    <style>
        .pricing-hero {
            background: linear-gradient(135deg, #000000 0%, #1a1a2e 50%, #16213e 100%);
            padding: 6rem 0 8rem;
            position: relative;
            overflow: hidden;
        }

        .pricing-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 70%, rgba(238, 49, 79, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(100, 100, 255, 0.1) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        .pricing-hero-content {
            position: relative;
            z-index: 1;
        }

        .pricing-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(238, 49, 79, 0.2);
            border: 1px solid rgba(238, 49, 79, 0.3);
            border-radius: 9999px;
            color: #ff6b7a;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1.5rem;
        }

        .pricing-hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .pricing-hero p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto;
        }

        .pricing-card-wrapper {
            margin-top: -4rem;
            position: relative;
            z-index: 2;
        }

        .pricing-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 480px;
            margin: 0 auto;
            overflow: hidden;
            position: relative;
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #EE314F, #ff6b7a, #EE314F);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .card-header {
            padding: 2.5rem;
            text-align: center;
            background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
        }

        .plan-label {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #000;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .price-container {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .price-currency {
            font-size: 1.5rem;
            font-weight: 600;
            color: #000;
            align-self: flex-start;
            margin-top: 0.5rem;
        }

        .price-amount {
            font-size: 5rem;
            font-weight: 800;
            color: #000;
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .price-period {
            font-size: 1rem;
            color: #868e96;
            font-weight: 500;
        }

        .price-per-site {
            font-size: 0.875rem;
            color: #495057;
            margin-top: 0.5rem;
        }

        .card-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 480px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .feature-item:hover {
            background: #f8f9fa;
        }

        .feature-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
            color: #12b886;
            margin-top: 0.125rem;
        }

        .feature-text {
            font-size: 0.875rem;
            color: #212529;
            font-weight: 500;
        }

        .cta-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 1.125rem 2rem;
            background: #000;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .cta-button:hover {
            background: #1a1a1a;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .cta-button svg {
            width: 1.25rem;
            height: 1.25rem;
            transition: transform 0.3s ease;
        }

        .cta-button:hover svg {
            transform: translateX(4px);
        }

        .guarantee-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #868e96;
        }

        .guarantee-badge svg {
            width: 1rem;
            height: 1rem;
        }

        /* Trust Section */
        .trust-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .trust-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        .trust-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem;
        }

        .trust-icon {
            width: 3rem;
            height: 3rem;
            margin-bottom: 1rem;
            color: #000;
        }

        .trust-title {
            font-size: 1rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 0.5rem;
        }

        .trust-text {
            font-size: 0.875rem;
            color: #868e96;
            line-height: 1.5;
        }

        /* Feedback Section */
        .feedback-section {
            padding: 4rem 0;
            background: white;
        }

        .feedback-card {
            background: #f8f9fa;
            border-radius: 1.25rem;
            padding: 2.5rem;
            max-width: 640px;
            margin: 0 auto;
        }

        .feedback-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #000;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .feedback-subtitle {
            font-size: 0.9375rem;
            color: #868e96;
            text-align: center;
            margin-bottom: 2rem;
        }

        .opinion-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .opinion-grid {
                grid-template-columns: 1fr;
            }
        }

        .opinion-option {
            position: relative;
        }

        .opinion-option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .opinion-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .opinion-label:hover {
            border-color: #ced4da;
            transform: translateY(-2px);
        }

        .opinion-option input:checked+.opinion-label {
            border-color: #000;
            background: #000;
        }

        .opinion-option input:checked+.opinion-label .opinion-emoji {
            transform: scale(1.2);
        }

        .opinion-option input:checked+.opinion-label .opinion-text {
            color: white;
        }

        .opinion-emoji {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            transition: transform 0.2s ease;
        }

        .opinion-text {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #495057;
            transition: color 0.2s ease;
        }

        .feedback-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 0.75rem;
            font-size: 0.9375rem;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.2s ease;
            font-family: inherit;
        }

        .feedback-textarea:focus {
            outline: none;
            border-color: #000;
        }

        .feedback-textarea::placeholder {
            color: #adb5bd;
        }

        .feedback-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .feedback-submit {
            display: block;
            width: auto;
            margin: 1.5rem auto 0;
            padding: 0.75rem 2rem;
            background: #000;
            color: white;
            font-size: 0.9375rem;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .feedback-submit:hover {
            background: #1a1a1a;
            transform: translateY(-1px);
        }

        .alert {
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(18, 184, 134, 0.1);
            color: #12b886;
            border: 1px solid rgba(18, 184, 134, 0.2);
        }

        .alert-error {
            background: rgba(250, 82, 82, 0.1);
            color: #fa5252;
            border: 1px solid rgba(250, 82, 82, 0.2);
        }

        /* FAQ Section */
        .faq-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }

        .faq-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #000;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            max-width: 900px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .faq-grid {
                grid-template-columns: 1fr;
            }
        }

        .faq-item {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .faq-question {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 0.75rem;
        }

        .faq-answer {
            font-size: 0.875rem;
            color: #868e96;
            line-height: 1.6;
        }

        /* Bottom CTA */
        .bottom-cta {
            padding: 3rem 0 4rem;
            text-align: center;
        }

        .bottom-cta p {
            color: #868e96;
            font-size: 0.9375rem;
        }

        .bottom-cta a {
            color: #EE314F;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .bottom-cta a:hover {
            color: #d62a44;
            text-decoration: underline;
        }

        .mb-3xl {
            margin-bottom: var(--spacing-3xl);
        }
    </style>

    <!-- Hero Section -->
    <div class="pricing-hero">
        <div class="container pricing-hero-content text-center">
            <div class="pricing-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon
                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
                Simple Pricing
            </div>
            <h1>One Plan. Everything Included.</h1>
            <p>No tiers, no confusion. Get all features at one transparent price.</p>
        </div>
    </div>

    <!-- Pricing Card -->
    <div class="container pricing-card-wrapper">
        <div class="pricing-card animate-fadeInUp">
            <div class="card-header">
                <span class="plan-label">Per Site</span>
                <div class="price-container">
                    <span class="price-currency">$</span>
                    <span class="price-amount">9</span>
                    <span class="price-amount" style="font-size: 2.5rem;">.99</span>
                </div>
                <div class="price-period">/month per site</div>
                <div class="price-per-site">Add unlimited sites, each at $9.99/mo</div>
            </div>

            <div class="card-body">
                <div class="features-grid">
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="feature-text">Twice Daily Screenshots</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="feature-text">Google Analytics</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="feature-text">Search Console</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="feature-text">Uptime Monitoring</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="feature-text">Visual Change Alerts</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="feature-text">Priority Support</span>
                    </div>
                </div>

                <a href="#" class="cta-button">
                    Get Started Now
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>

                <div class="guarantee-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Cancel anytime • No hidden fees
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="feedback-section">
        <div class="container">
            <div class="feedback-card animate-fadeInUp">
                <h3 class="feedback-title">What do you think?</h3>
                <p class="feedback-subtitle">Help us improve with your honest feedback.</p>

                @if(session('success'))
                    <div class="alert alert-success">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        ✕ {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('pricing.feedback') }}" method="POST">
                    @csrf

                    <div class="opinion-grid">
                        <label class="opinion-option">
                            <input type="radio" name="price_opinion" value="too_expensive">
                            <div class="opinion-label">
                                <span class="opinion-emoji">💸</span>
                                <span class="opinion-text">Too Expensive</span>
                            </div>
                        </label>

                        <label class="opinion-option">
                            <input type="radio" name="price_opinion" value="fair">
                            <div class="opinion-label">
                                <span class="opinion-emoji">⚖️</span>
                                <span class="opinion-text">Fair Price</span>
                            </div>
                        </label>

                        <label class="opinion-option">
                            <input type="radio" name="price_opinion" value="good_deal">
                            <div class="opinion-label">
                                <span class="opinion-emoji">💎</span>
                                <span class="opinion-text">Good Deal</span>
                            </div>
                        </label>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <label for="suggestion" class="feedback-label">Any suggestions?</label>
                        <textarea name="suggestion" id="suggestion" class="feedback-textarea"
                            placeholder="Tell us what would make this a no-brainer for you..."></textarea>
                    </div>

                    <button type="submit" class="feedback-submit">Submit Feedback</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Trust Section -->
    <div class="trust-section">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <div class="trust-title">Instant Setup</div>
                    <div class="trust-text">Add your site and start monitoring in under 2 minutes.</div>
                </div>
                <div class="trust-item">
                    <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <div class="trust-title">Secure & Private</div>
                    <div class="trust-text">Your data is encrypted and never shared with third parties.</div>
                </div>
                <div class="trust-item">
                    <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <div class="trust-title">Premium Support</div>
                    <div class="trust-text">Get help from our team whenever you need it.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <div class="container">
            <h2 class="faq-title">Frequently Asked Questions</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">Can I cancel anytime?</div>
                    <div class="faq-answer">Yes! There are no contracts or commitments. Cancel your subscription at any
                        time with just one click.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">How does per-site pricing work?</div>
                    <div class="faq-answer">Each website you add costs $9.99/month. Add as many sites as you need – each
                        gets the full feature set.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Is there a free trial?</div>
                    <div class="faq-answer">We offer a 14-day free trial for new users. No credit card required to
                        start.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">What payment methods do you accept?</div>
                    <div class="faq-answer">We accept all major credit cards, PayPal, and most local payment methods
                        through Stripe.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom CTA -->
    <div class="bottom-cta">
        <p>Have questions? <a href="#">Contact our support team</a></p>
    </div>
</x-app-layout>