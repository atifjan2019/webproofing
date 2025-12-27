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

        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--color-text);
            transition: all 0.3s;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            z-index: 1000;
            padding: 80px 20px 20px;
            flex-direction: column;
            gap: 0;
        }

        .mobile-menu.active {
            display: flex;
        }

        .mobile-menu a {
            display: block;
            padding: 1rem 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--color-text);
            text-decoration: none;
            border-bottom: 1px solid var(--color-border);
        }

        .mobile-menu a:last-child {
            border-bottom: none;
        }

        .mobile-menu .btn-primary {
            margin-top: 1rem;
            text-align: center;
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
            padding-top: 6rem;
            padding-bottom: 5rem;
            text-align: center;
            background: radial-gradient(circle at 50% 0%, rgba(238, 49, 79, 0.03) 0%, rgba(255, 255, 255, 0) 50%);
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
            }

            .nav-logo {
                font-size: 1.25rem;
            }

            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
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
                padding-top: 2.5rem;
                padding-bottom: 2.5rem;
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

            .hero-pills {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 0.75rem !important;
                justify-content: stretch !important;
            }

            .hero-pills>div {
                justify-content: center !important;
                width: 100% !important;
                padding: 0.5rem 0.25rem !important;
                font-size: 0.75rem !important;
            }

            .section {
                padding: 2.5rem 0;
            }

            .section-header {
                margin-bottom: 2rem;
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

            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }

            .stats-grid>div {
                padding: 1rem !important;
            }

            .stats-grid .stat-number {
                font-size: 2rem !important;
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
                <a href="#faq" class="nav-link">FAQ</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started Free</a>
                        @endif
                    @endauth
                @endif
            </div>
            <button class="hamburger" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('pricing') }}" onclick="toggleMobileMenu()">Pricing</a>
        <a href="#faq" onclick="toggleMobileMenu()">FAQ</a>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" onclick="toggleMobileMenu()">Dashboard</a>
            @else
                <a href="{{ route('login') }}" onclick="toggleMobileMenu()">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary" onclick="toggleMobileMenu()">Get Started Free</a>
                @endif
            @endauth
        @endif
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const hamburger = document.querySelector('.hamburger');
            menu.classList.toggle('active');
            hamburger.classList.toggle('active');
            document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
        }
    </script>

    <!-- Hero -->
    <section class="hero container">
        <span class="hero-tag" style="background: rgba(238, 49, 79, 0.1); color: #EE314F; font-weight: 600;">Visual
            monitoring for websites</span>
        <h1>
            Know your website is working<br>
            <span
                style="background: linear-gradient(135deg, #EE314F 0%, #ff6b7a 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">with
                visual proof</span>
        </h1>
        <p class="hero-subtitle"
            style="font-size: 1.125rem; line-height: 1.7; max-width: 540px; margin-bottom: 2.5rem;">
            WebProofing captures daily screenshots, runs PageSpeed tests, and connects your Google Analytics & Search
            Console.
            All your vital stats in one dashboard.
        </p>

        <!-- Quick value props pills -->
        <div class="hero-pills"
            style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem; margin-bottom: 2rem;">
            <div
                style="background: white; border: 1px solid var(--color-border); border-radius: 99px; padding: 0.4rem 0.875rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 500; color: var(--color-text); box-shadow: 0 2px 4px rgba(0,0,0,0.02); white-space: nowrap;">
                <span style="color: #10b981;">✓</span> Daily Screenshots
            </div>
            <div
                style="background: white; border: 1px solid var(--color-border); border-radius: 99px; padding: 0.4rem 0.875rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 500; color: var(--color-text); box-shadow: 0 2px 4px rgba(0,0,0,0.02); white-space: nowrap;">
                <span style="color: #10b981;">✓</span> PageSpeed Insights
            </div>
            <div
                style="background: white; border: 1px solid var(--color-border); border-radius: 99px; padding: 0.4rem 0.875rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 500; color: var(--color-text); box-shadow: 0 2px 4px rgba(0,0,0,0.02); white-space: nowrap;">
                <span style="color: #10b981;">✓</span> Google Analytics 4
            </div>
            <div
                style="background: white; border: 1px solid var(--color-border); border-radius: 99px; padding: 0.4rem 0.875rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 500; color: var(--color-text); box-shadow: 0 2px 4px rgba(0,0,0,0.02); white-space: nowrap;">
                <span style="color: #10b981;">✓</span> Search Console
            </div>
        </div>

        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg"
                style="box-shadow: 0 10px 20px -5px rgba(238, 49, 79, 0.4); padding: 1rem 2.5rem;">
                View Live Demo
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
            <p class="hero-note" style="font-size: 0.875rem; color: var(--color-text-muted);">
                <span style="color: #10b981;">●</span> 7-day free trial • No credit card required
            </p>
        </div>
    </section>

    <!-- Dashboard Preview Section -->
    <section class="section" style="padding: 3rem 0 5rem;">
        <div class="container">
            <div
                style="background: linear-gradient(145deg, #1a1a1a 0%, #2d2d2d 100%); border-radius: 1.5rem; padding: 2rem; box-shadow: 0 25px 60px rgba(0,0,0,0.15);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <div style="width: 12px; height: 12px; background: #ff5f56; border-radius: 50%;"></div>
                    <div style="width: 12px; height: 12px; background: #ffbd2e; border-radius: 50%;"></div>
                    <div style="width: 12px; height: 12px; background: #27ca40; border-radius: 50%;"></div>
                    <span style="color: rgba(255,255,255,0.5); font-size: 0.75rem; margin-left: 1rem;">WebProofing
                        Dashboard</span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <!-- Screenshot Card -->
                    <div style="background: #ffffff; border-radius: 1rem; padding: 1.25rem; text-align: center;">
                        <div
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 120px; border-radius: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center;">
                            <svg width="48" height="48" fill="none" stroke="white" viewBox="0 0 24 24"
                                style="opacity: 0.9;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">Today's Screenshot</p>
                        <p style="font-size: 0.625rem; color: #9ca3af;">Captured 2 hours ago</p>
                    </div>
                    <!-- GA4 Stats -->
                    <div style="background: #ffffff; border-radius: 1rem; padding: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#4285f4">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                            </svg>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #374151;">Google Analytics</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <div>
                                <p style="font-size: 1.25rem; font-weight: 700; color: #1a1a1a;">2,847</p>
                                <p style="font-size: 0.625rem; color: #6b7280;">Visitors</p>
                            </div>
                            <div>
                                <p style="font-size: 1.25rem; font-weight: 700; color: #1a1a1a;">8,421</p>
                                <p style="font-size: 0.625rem; color: #6b7280;">Page Views</p>
                            </div>
                        </div>
                        <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                            <span style="color: #10b981; font-size: 0.75rem; font-weight: 600;">↑ 12.5%</span>
                            <span style="color: #9ca3af; font-size: 0.625rem;">vs last week</span>
                        </div>
                    </div>
                    <!-- GSC Stats -->
                    <div style="background: #ffffff; border-radius: 1rem; padding: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#ea4335">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #374151;">Search Console</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <div>
                                <p style="font-size: 1.25rem; font-weight: 700; color: #1a1a1a;">1,249</p>
                                <p style="font-size: 0.625rem; color: #6b7280;">Clicks</p>
                            </div>
                            <div>
                                <p style="font-size: 1.25rem; font-weight: 700; color: #1a1a1a;">45.2K</p>
                                <p style="font-size: 0.625rem; color: #6b7280;">Impressions</p>
                            </div>
                        </div>
                        <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                            <span style="color: #10b981; font-size: 0.75rem; font-weight: 600;">↑ 8.3%</span>
                            <span style="color: #9ca3af; font-size: 0.625rem;">CTR improvement</span>
                        </div>
                    </div>
                    <!-- PageSpeed Score -->
                    <div style="background: #ffffff; border-radius: 1rem; padding: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#fbbc04">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #374151;">PageSpeed</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                            <div>
                                <p style="font-size: 1.25rem; font-weight: 700; color: #10b981;">92</p>
                                <p style="font-size: 0.625rem; color: #6b7280;">Mobile Score</p>
                            </div>
                            <div>
                                <p style="font-size: 1.25rem; font-weight: 700; color: #10b981;">98</p>
                                <p style="font-size: 0.625rem; color: #6b7280;">Desktop Score</p>
                            </div>
                        </div>
                        <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                            <span style="color: #10b981; font-size: 0.75rem; font-weight: 600;">✓ Passing</span>
                            <span style="color: #9ca3af; font-size: 0.625rem;">Core Web Vitals</span>
                        </div>
                    </div>
                </div>
            </div>
            <p style="text-align: center; color: var(--color-text-muted); font-size: 0.875rem; margin-top: 1.5rem;">
                ↑ This is what your dashboard will look like
            </p>
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
                    <h3>Daily Screenshots</h3>
                    <p>Twice-daily browser screenshots capture exactly what your site looks like—visual proof it's
                        working</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3>PageSpeed Insights</h3>
                    <p>Google PageSpeed scores for mobile & desktop. Track performance, Core Web Vitals, and
                        optimization tips</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3>Google Analytics 4</h3>
                    <p>Your GA4 data in one view—visitors, page views, sessions, bounce rate, and traffic trends over
                        time</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3>Search Console</h3>
                    <p>Track your Google search performance—clicks, impressions, CTR, average position, and top queries
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Stats / Value Props -->
    <section class="section" style="background: #000; color: white; padding: 3rem 0;">
        <div class="container">
            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                <!-- Stat 1 -->
                <div
                    style="background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 100%); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; padding: 1.5rem; text-align: center;">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #EE314F 0%, #ff6b7a 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="stat-number" style="font-size: 2.5rem; font-weight: 800; color: white; line-height: 1;">
                        2x</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem;">Daily
                        Screenshots</div>
                </div>
                <!-- Stat 2 -->
                <div
                    style="background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 100%); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; padding: 1.5rem; text-align: center;">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #34d399 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-number" style="font-size: 2.5rem; font-weight: 800; color: white; line-height: 1;">
                        7</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem;">Days of History
                    </div>
                </div>
                <!-- Stat 3 -->
                <div
                    style="background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 100%); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; padding: 1.5rem; text-align: center;">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <div class="stat-number" style="font-size: 2.5rem; font-weight: 800; color: white; line-height: 1;">
                        4-in-1</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem;">Dashboard</div>
                </div>
                <!-- Stat 4 -->
                <div
                    style="background: linear-gradient(145deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.02) 100%); border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; padding: 1.5rem; text-align: center;">
                    <div
                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div class="stat-number" style="font-size: 2.5rem; font-weight: 800; color: white; line-height: 1;">
                        0</div>
                    <div style="font-size: 0.875rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem;">Code Required
                    </div>
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
                <p>Get started in 3 simple steps</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <!-- Step 1 -->
                <div
                    style="background: white; border-radius: 1.25rem; padding: 2rem; border: 1px solid var(--color-border); position: relative;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div
                            style="width: 40px; height: 40px; background: #EE314F; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; font-weight: 700; color: white;">
                            1</div>
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--color-text); margin: 0;">Add your
                            website</h3>
                    </div>
                    <p style="font-size: 0.9375rem; color: var(--color-text-muted); line-height: 1.6; margin: 0;">Enter
                        your domain URL. We start capturing screenshots and running PageSpeed tests immediately.</p>
                </div>
                <!-- Step 2 -->
                <div
                    style="background: white; border-radius: 1.25rem; padding: 2rem; border: 1px solid var(--color-border); position: relative;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div
                            style="width: 40px; height: 40px; background: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; font-weight: 700; color: white;">
                            2</div>
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--color-text); margin: 0;">Connect
                            Google</h3>
                    </div>
                    <p style="font-size: 0.9375rem; color: var(--color-text-muted); line-height: 1.6; margin: 0;">
                        One-click OAuth to link your Analytics 4 and Search Console. Read-only access, completely
                        secure.</p>
                </div>
                <!-- Step 3 -->
                <div
                    style="background: white; border-radius: 1.25rem; padding: 2rem; border: 1px solid var(--color-border); position: relative;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div
                            style="width: 40px; height: 40px; background: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.125rem; font-weight: 700; color: white;">
                            3</div>
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--color-text); margin: 0;">Done!
                            Monitor daily</h3>
                    </div>
                    <p style="font-size: 0.9375rem; color: var(--color-text-muted); line-height: 1.6; margin: 0;">Sit
                        back. Screenshots, PageSpeed scores, and analytics data sync automatically twice daily.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Who it's for - Expanded with use cases -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Who it's for</h2>
                <p>Built for people who manage websites and need peace of mind.</p>
            </div>

            <!-- Use Cases Grid -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Freelancer Use Case -->
                <div
                    style="background: white; border: 1px solid var(--color-border); border-radius: 1.25rem; padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden;">
                    <div
                        style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #667eea, #764ba2);">
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                        <div
                            style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text);">Freelancers</h3>
                    </div>
                    <p
                        style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; margin-bottom: 1.25rem;">
                        "My client called saying their site had been broken for 3 days. I had no proof it was working.
                        Now I have daily screenshots to show them."
                    </p>
                    <div style="background: var(--color-bg-soft); border-radius: 0.75rem; padding: 1rem;">
                        <p
                            style="font-size: 0.8125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.5rem;">
                            ✓ Perfect for:</p>
                        <ul
                            style="font-size: 0.8125rem; color: var(--color-text-muted); padding-left: 1rem; margin: 0;">
                            <li>Client reporting & accountability</li>
                            <li>Managing multiple sites</li>
                            <li>Peace of mind while you sleep</li>
                        </ul>
                    </div>
                </div>

                <!-- Agency Use Case -->
                <div
                    style="background: white; border: 1px solid var(--color-border); border-radius: 1.25rem; padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden;">
                    <div
                        style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #f59e0b, #ef4444);">
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                        <div
                            style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text);">Agencies</h3>
                    </div>
                    <p
                        style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; margin-bottom: 1.25rem;">
                        "We manage 50+ client sites. Before WebProofing, we'd only know about issues when clients
                        complained. Now we catch problems before they do."
                    </p>
                    <div style="background: var(--color-bg-soft); border-radius: 0.75rem; padding: 1rem;">
                        <p
                            style="font-size: 0.8125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.5rem;">
                            ✓ Perfect for:</p>
                        <ul
                            style="font-size: 0.8125rem; color: var(--color-text-muted); padding-left: 1rem; margin: 0;">
                            <li>Proactive client management</li>
                            <li>Visual proof for SLAs</li>
                            <li>Centralized monitoring dashboard</li>
                        </ul>
                    </div>
                </div>

                <!-- SEO Pro Use Case -->
                <div
                    style="background: white; border: 1px solid var(--color-border); border-radius: 1.25rem; padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden;">
                    <div
                        style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #10b981, #3b82f6);">
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                        <div
                            style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-text);">SEO Professionals
                        </h3>
                    </div>
                    <p
                        style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; margin-bottom: 1.25rem;">
                        "I need to see both the visual state and the search performance together. When rankings drop, I
                        can see exactly what changed on the site."
                    </p>
                    <div style="background: var(--color-bg-soft); border-radius: 0.75rem; padding: 1rem;">
                        <p
                            style="font-size: 0.8125rem; font-weight: 600; color: var(--color-text); margin-bottom: 0.5rem;">
                            ✓ Perfect for:</p>
                        <ul
                            style="font-size: 0.8125rem; color: var(--color-text-muted); padding-left: 1rem; margin: 0;">
                            <li>Correlating visual changes with rankings</li>
                            <li>GA4 + GSC data in one place</li>
                            <li>Quick client status checks</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="audience-grid" style="margin-top: 2rem;">
                <span class="audience-tag">Freelancers</span>
                <span class="audience-tag">Agencies</span>
                <span class="audience-tag">SEO professionals</span>
                <span class="audience-tag">Website owners</span>
                <span class="audience-tag">Developers</span>
                <span class="audience-tag">E-commerce stores</span>
                <span class="audience-tag">Marketing teams</span>
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
                                Screenshots</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">PageSpeed
                                Insights (Mobile & Desktop)</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">Google
                                Analytics 4 Data</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">Search Console
                                Performance</span>
                        </div>
                        <div class="feature-item" style="padding: 0; align-items: center;">
                            <svg class="feature-icon-check" style="color: #10b981; width: 1.5rem; height: 1.5rem;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="feature-text" style="font-size: 1rem; color: var(--color-text);">7-Day Visual
                                History</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="btn btn-primary"
                        style="width: 100%; padding: 1rem; font-size: 1.125rem; margin-top: 1rem;">
                        Start 7-Day Free Trial
                    </a>

                    <div class="guarantee-badge" style="margin-top: 1.25rem; opacity: 0.7;">
                        <span>✓ Cancel anytime • No credit card required</span>
                    </div>
                </div>
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
                <div class="feature-card" style="position: relative; opacity: 0.85; border-style: dashed;">
                    <span
                        style="position: absolute; top: 1rem; right: 1rem; padding: 0.25rem 0.75rem; background: #f3f4f6; border-radius: 2rem; font-size: 0.6875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280;">Coming
                        Soon</span>
                    <div class="feature-icon" style="background: #f3f4f6;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #6b7280;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h3>Google Ads Integration</h3>
                    <p>Track your ad campaigns alongside organic performance. See spend, clicks, conversions, and ROAS
                        in one unified view.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section" id="faq">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
                <p>Everything you need to know about WebProofing</p>
            </div>

            <div style="max-width: 800px; margin: 0 auto;">
                <!-- FAQ Item 1 -->
                <div class="faq-item" style="border-bottom: 1px solid var(--color-border);">
                    <button class="faq-question" onclick="toggleFaq(this)"
                        style="width: 100%; padding: 1.25rem 0; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                        <span style="font-size: 1rem; font-weight: 600; color: var(--color-text);">What exactly does
                            WebProofing do?</span>
                        <svg class="faq-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="transition: transform 0.3s; flex-shrink: 0; color: var(--color-text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p
                            style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; padding-bottom: 1.25rem;">
                            WebProofing captures <strong>daily screenshots</strong>, runs <strong>PageSpeed
                                tests</strong>, and connects your <strong>Google Analytics 4</strong> and <strong>Search
                                Console</strong> data. Everything you need to monitor your website's health in one
                            unified dashboard.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item" style="border-bottom: 1px solid var(--color-border);">
                    <button class="faq-question" onclick="toggleFaq(this)"
                        style="width: 100%; padding: 1.25rem 0; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                        <span style="font-size: 1rem; font-weight: 600; color: var(--color-text);">How is this different
                            from uptime monitoring?</span>
                        <svg class="faq-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="transition: transform 0.3s; flex-shrink: 0; color: var(--color-text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p
                            style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; padding-bottom: 1.25rem;">
                            Uptime tools only check if your site returns a 200 status—they can't see if your homepage is
                            blank or layout is broken. WebProofing uses a real browser to capture what your site
                            <em>actually looks like</em>, plus measures PageSpeed performance.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item" style="border-bottom: 1px solid var(--color-border);">
                    <button class="faq-question" onclick="toggleFaq(this)"
                        style="width: 100%; padding: 1.25rem 0; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                        <span style="font-size: 1rem; font-weight: 600; color: var(--color-text);">Do I need to install
                            anything on my website?</span>
                        <svg class="faq-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="transition: transform 0.3s; flex-shrink: 0; color: var(--color-text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p
                            style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; padding-bottom: 1.25rem;">
                            <strong>No installation required.</strong> Just add your website URL and we start capturing
                            screenshots and PageSpeed data immediately. For GA4 and Search Console, connect your Google
                            account with one click.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item" style="border-bottom: 1px solid var(--color-border);">
                    <button class="faq-question" onclick="toggleFaq(this)"
                        style="width: 100%; padding: 1.25rem 0; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                        <span style="font-size: 1rem; font-weight: 600; color: var(--color-text);">What data do you
                            track?</span>
                        <svg class="faq-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="transition: transform 0.3s; flex-shrink: 0; color: var(--color-text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p
                            style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; padding-bottom: 1.25rem;">
                            <strong>Screenshots:</strong> Twice-daily visual captures. <strong>PageSpeed:</strong>
                            Mobile & desktop scores, Core Web Vitals. <strong>GA4:</strong> Visitors, page views,
                            sessions, bounce rate. <strong>Search Console:</strong> Clicks, impressions, CTR, rankings,
                            top queries.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item" style="border-bottom: 1px solid var(--color-border);">
                    <button class="faq-question" onclick="toggleFaq(this)"
                        style="width: 100%; padding: 1.25rem 0; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                        <span style="font-size: 1rem; font-weight: 600; color: var(--color-text);">Can I monitor
                            multiple websites?</span>
                        <svg class="faq-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="transition: transform 0.3s; flex-shrink: 0; color: var(--color-text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p
                            style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; padding-bottom: 1.25rem;">
                            Yes! Add as many sites as you need. Each site is <strong>$9.99/month</strong> with all
                            features included. Perfect for agencies and freelancers managing multiple client sites from
                            one dashboard.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)"
                        style="width: 100%; padding: 1.25rem 0; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; text-align: left;">
                        <span style="font-size: 1rem; font-weight: 600; color: var(--color-text);">Can I try before I
                            pay?</span>
                        <svg class="faq-icon" width="20" height="20" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="transition: transform 0.3s; flex-shrink: 0; color: var(--color-text-muted);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="faq-answer" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease;">
                        <p
                            style="color: var(--color-text-muted); font-size: 0.9375rem; line-height: 1.7; padding-bottom: 1.25rem;">
                            Absolutely! We offer a <strong>7-day free trial</strong> with no credit card required. Test
                            all features—screenshots, PageSpeed, GA4, and Search Console—before committing.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Still have questions -->
            <div
                style="text-align: center; margin-top: 3rem; padding: 2rem; background: var(--color-bg-soft); border-radius: 1.25rem;">
                <p style="font-size: 1rem; color: var(--color-text); font-weight: 600; margin-bottom: 0.5rem;">Still
                    have questions?</p>
                <p style="color: var(--color-text-muted); font-size: 0.9375rem;">
                    Reach out anytime at <a href="mailto:atifjan2019@gmail.com"
                        style="color: var(--color-accent); text-decoration: none; font-weight: 500;">atifjan2019@gmail.com</a>
                </p>
            </div>
        </div>
    </section>

    <script>
        function toggleFaq(button) {
            const item = button.parentElement;
            const answer = item.querySelector('.faq-answer');
            const icon = button.querySelector('.faq-icon');
            const isOpen = answer.style.maxHeight && answer.style.maxHeight !== '0px';

            // Close all other FAQs
            document.querySelectorAll('.faq-answer').forEach(a => a.style.maxHeight = '0px');
            document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = 'rotate(0deg)');

            if (!isOpen) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>

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