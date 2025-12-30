<x-app-layout>
    <style>
        /* PageSpeed Insights Custom Styles */
        /* Results Section */
        .psi-results {
            padding: 0;
            position: relative;
        }

        /* Main Score Ring */
        .score-ring-wrapper {
            text-align: center;
            padding: 2rem 0;
        }

        .score-ring-container {
            position: relative;
            width: 220px;
            height: 220px;
            margin: 0 auto 1.5rem;
        }

        .score-ring-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.02) 100%);
            border-radius: 50%;
            box-shadow: inset 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .score-ring-svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .score-ring-track {
            fill: none;
            stroke: #e5e7eb;
            stroke-width: 12;
        }

        .score-ring-progress {
            fill: none;
            stroke-width: 12;
            stroke-linecap: round;
            transition: stroke-dashoffset 1.5s ease-out, stroke 0.5s ease;
        }

        .score-ring-glow {
            fill: none;
            stroke-width: 16;
            stroke-linecap: round;
            filter: blur(8px);
            opacity: 0.4;
        }

        .score-value-wrapper {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .score-value {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
        }

        .score-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--color-text-muted);
            margin-top: 0.5rem;
        }

        .score-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 0.5rem;
        }

        .score-subtitle {
            font-size: 0.875rem;
            color: var(--color-text-muted);
        }

        /* Secondary Scores Grid */
        .secondary-scores {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 768px) {
            .secondary-scores {
                grid-template-columns: 1fr;
            }
        }

        .score-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            border: 1px solid var(--color-border);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .score-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--card-accent), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .score-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .score-card:hover::before {
            opacity: 1;
        }

        .score-card-ring {
            position: relative;
            width: 64px;
            height: 64px;
            flex-shrink: 0;
        }

        .score-card-svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .score-card-track {
            fill: none;
            stroke: #f3f4f6;
            stroke-width: 6;
        }

        .score-card-progress {
            fill: none;
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 1.5s ease-out;
        }

        .score-card-value {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
        }

        .score-card-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.25rem;
        }

        .score-card-info span {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Lab Data Section */
        .lab-data-section {
            background: white;
            border-radius: 24px;
            border: 1px solid var(--color-border);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .lab-data-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid var(--color-border);
        }

        .lab-data-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .lab-data-title h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .lab-data-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lab-data-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .strategy-badge {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .lab-data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        @media (max-width: 768px) {
            .lab-data-grid {
                grid-template-columns: 1fr;
            }
        }

        .metric-card {
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid var(--color-border);
            border-right: 1px solid var(--color-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card:nth-child(2n) {
            border-right: none;
        }

        @media (max-width: 768px) {
            .metric-card {
                border-right: none;
            }
        }

        .metric-card:nth-last-child(-n+2) {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .metric-card:last-child {
                border-bottom: none;
            }

            .metric-card:nth-last-child(2) {
                border-bottom: 1px solid var(--color-border);
            }
        }

        .metric-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--metric-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .metric-card:hover {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.01) 0%, rgba(0, 0, 0, 0.03) 100%);
        }

        .metric-card:hover::before {
            transform: scaleY(1);
        }

        .metric-info {
            flex: 1;
        }

        .metric-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .metric-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--metric-color);
            box-shadow: 0 0 12px var(--metric-color);
        }

        .metric-name {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .metric-abbr {
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.5rem;
            background: var(--color-bg-secondary);
            color: var(--color-text-muted);
            border-radius: 4px;
            letter-spacing: 0.05em;
        }

        .metric-desc {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
            line-height: 1.5;
            padding-left: 1.625rem;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--color-text-primary);
            min-width: 100px;
            text-align: right;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, rgba(238, 49, 79, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: emptyPulse 3s ease-in-out infinite;
        }

        @keyframes emptyPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(238, 49, 79, 0.2);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 40px 10px rgba(238, 49, 79, 0.1);
            }
        }

        .empty-state-icon svg {
            width: 48px;
            height: 48px;
            color: var(--color-accent);
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: 0.75rem;
        }

        .empty-state p {
            font-size: 1rem;
            color: var(--color-text-muted);
            max-width: 400px;
            margin: 0 auto;
        }

        /* Error State */
        .error-alert {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .error-alert svg {
            width: 24px;
            height: 24px;
            color: #ef4444;
            flex-shrink: 0;
        }

        .error-alert span {
            color: #ef4444;
            font-weight: 500;
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        /* Number counter animation */
        .score-counter {
            display: inline-block;
        }

        /* New Header Controls Styles */
        .header-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .strategy-toggle-clean {
            display: flex;
            background: #f1f5f9;
            border-radius: 0.5rem;
            padding: 0.25rem;
        }

        .strategy-btn-clean {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .strategy-btn-clean:hover {
            color: #0f172a;
        }

        .strategy-btn-clean.active {
            background: white;
            color: #0f172a;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
    </style>

    <div class="container" x-data="pageSpeedTest(@js($metrics ?? []))">
        <!-- Header -->
        <div class="flex flex-col gap-lg mb-lg" style="flex-direction: column;">
            <div class="flex items-center gap-md">
                <a href="{{ route('sites.index') }}" class="btn btn-secondary btn-icon">
                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-black">{{ $site->domain }}</h1>
                    <p class="text-muted text-sm mt-xs">Site Overview & Analytics</p>
                </div>
            </div>

            <!-- Controls Row -->
            <div class="flex flex-wrap items-center justify-between gap-md mobile-stack-header">

                <!-- Strategy Toggle & Badge -->
                <div class="header-controls">
                    <div class="strategy-toggle-clean">
                        <button @click="strategy = 'mobile'" class="strategy-btn-clean"
                            :class="strategy === 'mobile' ? 'active' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Mobile
                        </button>
                        <button @click="strategy = 'desktop'" class="strategy-btn-clean"
                            :class="strategy === 'desktop' ? 'active' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Desktop
                        </button>
                    </div>

                    <!-- Analyzing Badge -->
                    <div class="hidden md:flex items-center gap-xs px-3 py-1 bg-gray-100 rounded-lg text-xs text-muted">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span class="truncate max-w-[200px]">{{ str_starts_with($site->domain, 'http') ? $site->domain :
    'https://' . $site->domain }}</span>
                    </div>
                </div>

                <!-- Retest Button -->
                @if($trialStatus['can_monitor'])
                    <button @click="runTest" class="btn btn-primary btn-full-mobile" :disabled="loading">
                        <template x-if="!loading">
                            <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </template>
                        <div x-show="loading"
                            class="spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin">
                        </div>
                        <span x-text="loading ? 'Analyzing...' : 'Run Analysis'"></span>
                    </button>
                @else
                    <form action="{{ route('billing.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-full-mobile">Upgrade to Analyze</button>
                    </form>
                @endif
            </div>
        </div>

        @include('sites.partials.nav')

        <!-- Results Section -->
        <div class="psi-results mt-xl">
            <!-- Error Message -->
            <div x-show="error" x-cloak class="error-alert animate-fade-in">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="error"></span>
            </div>

            <!-- Results -->
            <div x-show="results" x-transition:enter="animate-fade-in">
                <!-- Main Performance Score -->
                <div class="score-ring-wrapper animate-slide-up">
                    <div class="score-ring-container">
                        <div class="score-ring-bg"></div>
                        <svg class="score-ring-svg" viewBox="0 0 220 220">
                            <!-- Track -->
                            <circle class="score-ring-track" cx="110" cy="110" r="95" />
                            <!-- Glow -->
                            <circle class="score-ring-glow" cx="110" cy="110" r="95"
                                :stroke="getScoreColor(results?.scores?.performance)" stroke-dasharray="597"
                                :stroke-dashoffset="597 - (597 * (results?.scores?.performance || 0) / 100)" />
                            <!-- Progress -->
                            <circle class="score-ring-progress" cx="110" cy="110" r="95"
                                :stroke="getScoreColor(results?.scores?.performance)" stroke-dasharray="597"
                                :stroke-dashoffset="597 - (597 * (results?.scores?.performance || 0) / 100)" />
                        </svg>
                        <div class="score-value-wrapper">
                            <span class="score-value" x-text="Math.round(results?.scores?.performance || 0)"></span>
                            <span class="score-label">out of 100</span>
                        </div>
                    </div>
                    <h2 class="score-title">Performance Score</h2>
                    <p class="score-subtitle">Core Web Vitals Assessment</p>
                </div>

                <!-- Secondary Scores -->
                <div class="secondary-scores">
                    <template x-for="(config, key) in {
                        accessibility: { label: 'Accessibility', color: '#6366f1' },
                        best_practices: { label: 'Best Practices', color: '#8b5cf6' },
                        seo: { label: 'SEO', color: '#06b6d4' }
                    }">
                        <div class="score-card animate-slide-up delay-200" :style="'--card-accent: ' + config.color">
                            <div class="score-card-ring">
                                <svg class="score-card-svg" viewBox="0 0 64 64">
                                    <circle class="score-card-track" cx="32" cy="32" r="26" />
                                    <circle class="score-card-progress" cx="32" cy="32" r="26" :stroke="config.color"
                                        stroke-dasharray="163"
                                        :stroke-dashoffset="163 - (163 * (results?.scores?.[key] || 0) / 100)" />
                                </svg>
                                <div class="score-card-value" :style="'color: ' + config.color"
                                    x-text="Math.round(results?.scores?.[key] || 0)"></div>
                            </div>
                            <div class="score-card-info">
                                <h4 x-text="config.label"></h4>
                                <span>Score</span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Lab Data Metrics -->
                <div class="lab-data-section animate-slide-up delay-300">
                    <div class="lab-data-header">
                        <div class="lab-data-title">
                            <div class="lab-data-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3>Lab Data Metrics</h3>
                        </div>
                        <span class="strategy-badge" x-text="strategy"></span>
                    </div>

                    <div class="lab-data-grid">
                        <!-- LCP -->
                        <div class="metric-card" style="--metric-color: #3b82f6;">
                            <div class="metric-info">
                                <div class="metric-header">
                                    <div class="metric-dot"></div>
                                    <span class="metric-name">Largest Contentful Paint</span>
                                    <span class="metric-abbr">LCP</span>
                                </div>
                                <p class="metric-desc">Time when the largest content element is painted.</p>
                            </div>
                            <div class="metric-value" x-text="results?.metrics?.lcp || '-'"></div>
                        </div>

                        <!-- FCP -->
                        <div class="metric-card" style="--metric-color: #10b981;">
                            <div class="metric-info">
                                <div class="metric-header">
                                    <div class="metric-dot"></div>
                                    <span class="metric-name">First Contentful Paint</span>
                                    <span class="metric-abbr">FCP</span>
                                </div>
                                <p class="metric-desc">Time when the first text or image is painted.</p>
                            </div>
                            <div class="metric-value" x-text="results?.metrics?.fcp || '-'"></div>
                        </div>

                        <!-- CLS -->
                        <div class="metric-card" style="--metric-color: #f59e0b;">
                            <div class="metric-info">
                                <div class="metric-header">
                                    <div class="metric-dot"></div>
                                    <span class="metric-name">Cumulative Layout Shift</span>
                                    <span class="metric-abbr">CLS</span>
                                </div>
                                <p class="metric-desc">Movement of visible elements within the viewport.</p>
                            </div>
                            <div class="metric-value" x-text="results?.metrics?.cls || '-'"></div>
                        </div>

                        <!-- TBT -->
                        <div class="metric-card" style="--metric-color: #ef4444;">
                            <div class="metric-info">
                                <div class="metric-header">
                                    <div class="metric-dot"></div>
                                    <span class="metric-name">Total Blocking Time</span>
                                    <span class="metric-abbr">TBT</span>
                                </div>
                                <p class="metric-desc">Sum of all time periods where task length > 50ms.</p>
                            </div>
                            <div class="metric-value" x-text="results?.metrics?.tbt || '-'"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div x-show="!results && !loading && !error" class="empty-state animate-fade-in">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3>Ready to Analyze</h3>
                <p>Select a device type and click "Run Analysis" to measure your website's performance.</p>
            </div>
        </div>
    </div>

    <script>
        function pageSpeedTest(storedMetrics) {
            return {
                strategy: 'mobile',
                loading: false,
                error: null,
                results: null,
                metrics: storedMetrics || {},

                init() {
                    this.loadStoredMetrics();
                    this.$watch('strategy', () => this.loadStoredMetrics());
                },

                loadStoredMetrics() {
                    this.results = null;
                    if (this.metrics && this.metrics[this.strategy]) {
                        const m = this.metrics[this.strategy];
                        this.results = {
                            scores: {
                                performance: m.performance_score,
                                seo: m.seo_score,
                                accessibility: m.accessibility_score,
                                best_practices: m.best_practices_score,
                            },
                            metrics: {
                                lcp: m.lcp,
                                fcp: m.fcp,
                                cls: m.cls,
                                tbt: m.tbt,
                                speed_index: m.speed_index,
                            }
                        };
                    }
                },

                async runTest() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        const res = await fetch('{{ route("sites.pagespeed.analyze", $site) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                strategy: this.strategy
                            })
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            throw new Error(data.error || 'Failed to fetching metrics');
                        }

                        this.results = data;

                        // Update stored metrics locally
                        if (!this.metrics) this.metrics = {};
                        this.metrics[this.strategy] = {
                            performance_score: data.scores.performance,
                            seo_score: data.scores.seo,
                            accessibility_score: data.scores.accessibility,
                            best_practices_score: data.scores.best_practices,
                            lcp: data.metrics.lcp,
                            fcp: data.metrics.fcp,
                            cls: data.metrics.cls,
                            tbt: data.metrics.tbt,
                            speed_index: data.metrics.speed_index,
                        };

                    } catch (e) {
                        this.error = e.message;
                        this.results = null;
                    } finally {
                        this.loading = false;
                    }
                },

                getScoreColor(score) {
                    if (score >= 90) return '#10B981'; // Success Green
                    if (score >= 50) return '#F59E0B'; // Warning Orange
                    return '#EF4444'; // Error Red
                }
            }
        }
    </script>
</x-app-layout>