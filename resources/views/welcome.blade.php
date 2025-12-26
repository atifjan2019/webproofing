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

        /* Early notice */
        .notice {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: var(--radius-lg);
            padding: 2rem;
        }

        .notice h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #92400e;
        }

        .notice p {
            color: #a16207;
            font-size: 1rem;
        }

        /* CTA */
        .cta {
            text-align: center;
            padding: 5rem 0;
        }

        .cta h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta p {
            color: var(--color-text-muted);
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }

        .cta-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--color-border);
            padding: 2rem 0;
            text-align: center;
        }

        .footer p {
            color: var(--color-text-muted);
            font-size: 0.875rem;
        }

        /* Mobile */
        @media (max-width: 640px) {
            .nav-links {
                gap: 1rem;
            }

            .hero {
                padding: 3rem 0;
            }

            .hero h1 {
                font-size: 1.75rem;
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
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <header class="container">
        <nav class="nav">
            <a href="/" class="nav-logo">Web<span>Proofing</span></a>
            <div class="nav-links">
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
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">View Demo</a>
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

    <!-- Early product notice -->
    <section class="section">
        <div class="container">
            <div class="notice">
                <h3>🚀 Early product</h3>
                <p>WebProofing is actively being developed. We'd love your feedback to make it better. Features are
                    being added regularly based on what users need.</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="cta container">
        <h2>Ready to try it?</h2>
        <p>See what WebProofing can do for your websites.</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">View Demo</a>
            <span class="hero-note">Early product – feedback welcome</span>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} WebProofing. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>