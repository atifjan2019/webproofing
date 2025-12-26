<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebProofing – Visual proof your website is working</title>
    <meta name="description"
        content="WebProofing automatically captures real browser screenshots and connects your Google Analytics & Search Console data. Visual proof + performance data in one simple dashboard.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --color-bg: #ffffff;
            --color-bg-soft: #f8f9fa;
            --color-text: #1a1a1a;
            --color-text-muted: #6b7280;
            --color-accent: #EE314F;
            --color-accent-hover: #d62a44;
            --color-border: #e5e7eb;
            --radius: 0.75rem;
            --radius-lg: 1rem;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navigation */
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
        }

        .nav-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text);
            text-decoration: none;
        }

        .nav-logo span {
            color: var(--color-accent);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-link {
            color: var(--color-text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--color-text);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--color-accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--color-accent-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--color-bg-soft);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .btn-secondary:hover {
            background: #eef0f2;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1rem;
        }

        /* Hero */
        .hero {
            padding: 5rem 0 4rem;
            text-align: center;
        }

        .hero-tag {
            display: inline-block;
            background: var(--color-bg-soft);
            color: var(--color-text-muted);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--color-text-muted);
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .hero-cta {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .hero-note {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }

        /* Sections */
        .section {
            padding: 4rem 0;
        }

        .section-alt {
            background: var(--color-bg-soft);
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3rem;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.01em;
        }

        .section-header p {
            color: var(--color-text-muted);
            font-size: 1.125rem;
        }

        /* What is WebProofing */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--color-bg);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            background: rgba(238, 49, 79, 0.1);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .feature-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--color-accent);
        }

        .feature-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            color: var(--color-text-muted);
            font-size: 0.9375rem;
        }

        /* Problem */
        .problem-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .problem-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .problem-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: #fef2f2;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .problem-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #ef4444;
        }

        .problem-item h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .problem-item p {
            color: var(--color-text-muted);
            font-size: 0.9375rem;
        }

        /* Steps */
        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .step {
            text-align: center;
        }

        .step-number {
            width: 3rem;
            height: 3rem;
            background: var(--color-text);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 auto 1rem;
        }

        .step h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .step p {
            color: var(--color-text-muted);
            font-size: 0.9375rem;
        }

        /* Who it's for */
        .audience-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }

        .audience-tag {
            background: var(--color-bg);
            border: 1px solid var(--color-border);
            border-radius: 2rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        /* Different */
        .diff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .diff-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .diff-check {
            width: 1.5rem;
            height: 1.5rem;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        .diff-check svg {
            width: 0.875rem;
            height: 0.875rem;
            color: #16a34a;
        }

        .diff-item span {
            font-size: 1rem;
            font-weight: 500;
        }

        /* Pricing & Poll Styles */
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

        .feature-icon-check {
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

        .guarantee-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #868e96;
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

        .feedback-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .feedback-submit {
            display: block;
            width: 100%;
            padding: 1rem 2rem;
            background: #000;
            color: white;
            font-size: 0.9375rem;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .feedback-submit:hover {
            background: #1a1a1a;
            transform: translateY(-1px);
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

        .cta {
            padding: 6rem 0;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .cta p {
            color: var(--color-text-muted);
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }

        .footer {
            padding: 4rem 0;
            background: var(--color-bg);
            border-top: 1px solid var(--color-border);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .footer-nav {
            display: flex;
            gap: 2rem;
        }

        .footer-nav a {
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .footer-nav a:hover {
            color: var(--color-accent);
        }

        .copyright {
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }

        /* Mobile */
        @media (max-width: 640px) {
            .container {
                padding: 0 1rem;
            }

            .nav {
                padding: 1rem 0;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .nav-logo {
                font-size: 1.25rem;
            }

            .nav-links {
                gap: 0.75rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .nav-link {
                font-size: 0.875rem;
            }

            .btn {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                min-height: 44px;
            }

            .btn-lg {
                padding: 0.875rem 1.5rem;
                font-size: 0.9375rem;
            }

            .hero {
                padding: 2.5rem 0;
            }

            .hero h1 {
                font-size: 1.75rem;
                line-height: 1.3;
            }

            .hero h1 br {
                display: none;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section {
                padding: 3rem 0;
            }

            .section-header h2 {
                font-size: 1.5rem;
            }

            .section-header p {
                font-size: 1rem;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .feature-card {
                padding: 1.25rem;
            }

            .feature-card h3 {
                font-size: 1rem;
            }

            .feature-card p {
                font-size: 0.875rem;
            }

            .problem-list {
                grid-template-columns: 1fr;
            }

            .steps {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .diff-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .audience-grid {
                gap: 0.5rem;
            }

            .audience-tag {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .pricing-card {
                margin: 0 -0.5rem;
                border-radius: 1rem;
            }

            .card-header {
                padding: 2rem 1.5rem;
            }

            .price-amount {
                font-size: 4rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .feature-item {
                padding: 0.5rem;
            }

            .cta-button {
                padding: 1rem 1.5rem;
                font-size: 0.9375rem;
            }

            .feedback-card {
                padding: 1.5rem;
                margin-top: 2.5rem;
                border-radius: 1rem;
            }

            .feedback-title {
                font-size: 1.25rem;
            }

            .opinion-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .opinion-label {
                padding: 1rem;
                flex-direction: row;
                gap: 0.75rem;
            }

            .opinion-emoji {
                font-size: 1.5rem;
                margin-bottom: 0;
            }

            .opinion-text {
                font-size: 0.875rem;
            }

            .feedback-textarea {
                min-height: 80px;
            }

            .cta {
                padding: 3rem 0;
            }

            .cta>div {
                padding: 2.5rem 1.5rem !important;
                border-radius: 1.25rem !important;
            }

            .cta h2 {
                font-size: 1.75rem;
            }

            .cta p {
                font-size: 1rem;
            }

            .footer {
                padding: 2.5rem 0;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .footer-nav {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
        }

        /* Tablet */
        @media (min-width: 641px) and (max-width: 1023px) {
            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .pricing-card {
                max-width: 420px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <header class="container">
        <nav class="nav">
            <a href="/" class="nav-logo">Web<span>Proofing</span></a>
            <div class="nav-links">
                <a href="{{ route('pricing') }}" class="nav-link">Pricing</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <!-- Hero -->
    <section class="hero container">
        <span class="hero-tag">Visual monitoring for websites</span>
        <h1>Know your website is working—<br>with visual proof</h1>
        <p class="hero-subtitle">
            WebProofing captures real browser screenshots and connects your Google Analytics & Search Console data.
            Visual proof + performance data in one simple dashboard.
        </p>
        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">View Demo</a>
            <span class="hero-note">Early product – feedback welcome</span>
        </div>
    </section>

    <!-- What is WebProofing -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>What is WebProofing?</h2>
                <p>A simple tool that shows you exactly what your website looks like and how it's
                    performing—automatically.</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3>Real browser screenshots</h3>
                    <p>Automatically captures what your site actually looks like, not just if it's "up"</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3>Google Analytics (GA4)</h3>
                    <p>See your traffic, visitors, and page views right alongside your screenshots</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3>Search Console</h3>
                    <p>Track your search performance—clicks, impressions, and rankings</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>Visual history</h3>
                    <p>See changes over time. Know exactly what happened and when</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Why we built this</h2>
                <p>Existing tools leave gaps. We wanted something simpler.</p>
            </div>
            <div class="problem-list">
                <div class="problem-item">
                    <div class="problem-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <h4>Uptime tools don't show what your site looked like</h4>
                        <p>They tell you "it's up" but not if something broke visually</p>
                    </div>
                </div>
                <div class="problem-item">
                    <div class="problem-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <h4>Clients ask "was my site really working?"</h4>
                        <p>Now you have visual proof to show them</p>
                    </div>
                </div>
                <div class="problem-item">
                    <div class="problem-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <h4>Analytics are scattered across different tools</h4>
                        <p>WebProofing brings screenshots and data together in one place</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>How it works</h2>
                <p>Three simple steps. No technical setup required.</p>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Add your website</h3>
                    <p>Just enter your domain. That's it.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Connect Google services</h3>
                    <p>Link your Analytics and Search Console with one click</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Let it run</h3>
                    <p>WebProofing automatically captures screenshots and data over time</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Who it's for -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Who it's for</h2>
                <p>Built for people who manage websites and need peace of mind.</p>
            </div>
            <div class="audience-grid">
                <span class="audience-tag">Freelancers</span>
                <span class="audience-tag">Agencies</span>
                <span class="audience-tag">SEO professionals</span>
                <span class="audience-tag">Website owners</span>
                <span class="audience-tag">Developers</span>
            </div>
        </div>
    </section>

    <!-- What makes it different -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>What makes it different</h2>
            </div>
            <div class="diff-grid">
                <div class="diff-item">
                    <div class="diff-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Real browser screenshots, not pings</span>
                </div>
                <div class="diff-item">
                    <div class="diff-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Visual proof + performance data together</span>
                </div>
                <div class="diff-item">
                    <div class="diff-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Simple, clean dashboard</span>
                </div>
                <div class="diff-item">
                    <div class="diff-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span>Built for clarity, not complexity</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="section" id="pricing" style="background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);">
        <div class="container">
            <div class="section-header">
                <span
                    style="color: var(--color-accent); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; margin-bottom: 0.5rem; display: block;">Plan
                    & Pricing</span>
                <h2>Simple, transparent pricing</h2>
                <p>Everything we offer, for one clear price. No hidden fees or complex tiers.</p>
            </div>

            <!-- Pricing Card -->
            <div class="pricing-card"
                style="margin: 0 auto; box-shadow: 0 30px 60px -12px rgba(0,0,0,0.12); border: 1px solid var(--color-border); border-radius: 2rem;">
                <div class="card-header" style="background: #ffffff; padding: 3rem 2rem;">
                    <span class="plan-label"
                        style="background: var(--color-accent); border-radius: 99px; padding: 0.4rem 1rem;">PRO
                        PLAN</span>
                    <div class="price-container" style="margin: 1.5rem 0 0.5rem;">
                        <span class="price-currency" style="color: var(--color-text);">$</span>
                        <span class="price-amount" style="color: var(--color-text); font-size: 5.5rem;">9.99</span>
                    </div>
                    <div class="price-period" style="font-size: 1.1rem; color: var(--color-text-muted);">per month, per
                        site</div>
                </div>

                <div class="card-body" style="padding: 2.5rem 3rem;">
                    <div class="features-grid" style="grid-template-columns: 1fr; gap: 1.25rem; margin-bottom: 2.5rem;">
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">Twice Daily
                                Automated Screenshots</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">Full Google
                                Analytics 4 Data</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">Search Console
                                Performance Tracking</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">Visual Uptime
                                Proof History</span>
                        </div>
                    </div>

                    <!-- Button removed as per user request -->

                    <div class="guarantee-badge" style="margin-top: 1.25rem; opacity: 0.7;">
                        <span>✓ Cancel anytime • No credit card required</span>
                    </div>
                </div>
            </div>

            <!-- Poll Section -->
            <div class="feedback-card"
                style="margin-top: 6rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 2rem; padding: 3.5rem 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                <div style="text-align: center; margin-bottom: 3rem;">
                    <h3 class="feedback-title" style="font-size: 1.75rem; margin-bottom: 0.75rem;">Is this pricing right
                        for you?</h3>
                    <p class="feedback-subtitle" style="font-size: 1rem; color: var(--color-text-muted);">We are
                        building WebProofing for YOU. Tell us what you think.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success" style="max-width: 500px; margin: 0 auto 2rem;">
                        ✓ {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('pricing.feedback') }}" method="POST" style="max-width: 600px; margin: 0 auto;">
                    @csrf

                    <div class="opinion-grid" style="gap: 1.25rem; margin-bottom: 2.5rem;">
                        <label class="opinion-option">
                            <input type="radio" name="price_opinion" value="too_expensive">
                            <div class="opinion-label" style="padding: 1.5rem 1rem; border-radius: 1.25rem;">
                                <span class="opinion-emoji" style="font-size: 2.5rem;">💸</span>
                                <span class="opinion-text" style="margin-top: 0.5rem; font-weight: 700;">Too High</span>
                            </div>
                        </label>

                        <label class="opinion-option">
                            <input type="radio" name="price_opinion" value="fair">
                            <div class="opinion-label" style="padding: 1.5rem 1rem; border-radius: 1.25rem;">
                                <span class="opinion-emoji" style="font-size: 2.5rem;">✅</span>
                                <span class="opinion-text" style="margin-top: 0.5rem; font-weight: 700;">Fair
                                    Price</span>
                            </div>
                        </label>

                        <label class="opinion-option">
                            <input type="radio" name="price_opinion" value="good_deal">
                            <div class="opinion-label" style="padding: 1.5rem 1rem; border-radius: 1.25rem;">
                                <span class="opinion-emoji" style="font-size: 2.5rem;">💎</span>
                                <span class="opinion-text" style="margin-top: 0.5rem; font-weight: 700;">Steal!</span>
                            </div>
                        </label>
                    </div>

                    <div style="margin-bottom: 2.5rem;">
                        <label for="suggestion" class="feedback-label"
                            style="font-size: 0.9375rem; color: var(--color-text); margin-bottom: 0.75rem;">What
                            features are we missing?</label>
                        <textarea name="suggestion" id="suggestion" class="feedback-textarea"
                            style="border-radius: 1rem; padding: 1.25rem; min-height: 120px;"
                            placeholder="I would pay more if WebProofing could..."></textarea>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="feedback-submit"
                            style="background: var(--color-accent); border-radius: 99px; padding: 1rem 3rem; font-weight: 700; font-size: 1rem; width: 100%;">Submit
                            Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Upcoming Features -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2>Coming Soon</h2>
                <p>Features we're actively building based on user feedback.</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card" style="position: relative; opacity: 0.85; border-style: dashed;">
                    <span
                        style="position: absolute; top: 1rem; right: 1rem; padding: 0.25rem 0.75rem; background: #f3f4f6; border-radius: 2rem; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Coming
                        Soon</span>
                    <div class="feature-icon" style="background: #f3f4f6;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #6b7280;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3>Smartlook Integration</h3>
                    <p>Session recordings and behavior insights. Watch how visitors interact with your website and
                        identify UX issues.</p>
                </div>
                <div class="feature-card" style="position: relative; opacity: 0.85; border-style: dashed;">
                    <span
                        style="position: absolute; top: 1rem; right: 1rem; padding: 0.25rem 0.75rem; background: #f3f4f6; border-radius: 2rem; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Coming
                        Soon</span>
                    <div class="feature-icon" style="background: #f3f4f6;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #6b7280;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3>Weekly Reports</h3>
                    <p>Automated email digests. Get weekly summaries of your website's health, screenshots, and
                        analytics delivered to your inbox.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Early product notice -->
    <section class="section">
        <div class="container">
            <div class="notice"
                style="background: rgba(238, 49, 79, 0.03); border: 1px solid rgba(238, 49, 79, 0.1); border-radius: 1.5rem; padding: 3rem 2rem; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🚀</div>
                <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--color-text);">Early
                    product feedback</h3>
                <p style="color: var(--color-text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                    WebProofing is actively being developed. We build based on what you need. Join us in shaping the
                    future of visual monitoring.</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="cta container">
        <div
            style="background: var(--color-text); color: white; padding: 5rem 2rem; border-radius: 2rem; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <h2 style="color: white; margin-bottom: 1rem;">Ready to see the proof?</h2>
            <p style="color: rgba(255,255,255,0.7); max-width: 500px; margin: 0 auto 2.5rem;">Join the early users
                getting visual certainty for their websites.</p>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg"
                    style="background: white; color: var(--color-text); padding: 1.25rem 3rem;">View Live Demo</a>
                <span style="font-size: 0.875rem; color: rgba(255,255,255,0.5);">Instant access • No obligations</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-content">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <a href="/" class="nav-logo" style="font-size: 1.25rem;">Web<span>Proofing</span></a>
                <p class="copyright">&copy; {{ date('Y') }} WebProofing. Made for clarity.</p>
            </div>

            <nav class="footer-nav">
                <a href="{{ route('pricing') }}">Pricing</a>
                <a href="{{ route('docs') }}">Documentation</a>
                <a href="mailto:atifjan2019@gmail.com">Support</a>
            </nav>
        </div>
    </footer>
</body>

</html>