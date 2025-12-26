<x-app-layout>
    <div class="container pb-2xl">
        <!-- Header -->
        <div class="flex flex-col gap-lg mb-lg">
            <div class="flex items-center gap-md">
                <a href="{{ route('sites.index') }}" class="btn btn-secondary btn-icon">
                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-black">{{ $site->domain }}</h1>
                    <p class="text-muted text-sm mt-xs">Website Speed Test</p>
                </div>
            </div>
        </div>

        @include('sites.partials.nav')

        <div class="mt-xl" x-data="pageSpeedTest(@js($metrics ?? []))">

            <!-- Controls Card -->
            <div class="card p-lg mb-xl bg-white border border-border shadow-sm rounded-lg">
                <div class="flex flex-col md:flex-row items-center justify-between gap-lg">

                    <!-- URL Display -->
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-bold text-muted uppercase tracking-wider mb-xs">Analyzing
                            URL</label>
                        <div
                            class="flex items-center px-md py-sm bg-gray-50 border border-border rounded text-sm text-gray-600 font-mono">
                            <span
                                class="truncate">{{ str_starts_with($site->domain, 'http') ? $site->domain : 'https://' . $site->domain }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-md w-full md:w-auto mt-md md:mt-0">
                        <!-- Strategy Toggle -->
                        <div class="flex bg-gray-100 p-1 rounded-md">
                            <button @click="strategy = 'mobile'; loadStoredMetrics()"
                                class="px-md py-xs rounded text-sm font-medium transition-all"
                                :class="strategy === 'mobile' ? 'bg-white text-black shadow-sm' : 'text-muted hover:text-gray-900'">
                                Mobile
                            </button>
                            <button @click="strategy = 'desktop'; loadStoredMetrics()"
                                class="px-md py-xs rounded text-sm font-medium transition-all"
                                :class="strategy === 'desktop' ? 'bg-white text-black shadow-sm' : 'text-muted hover:text-gray-900'">
                                Desktop
                            </button>
                        </div>

                        <!-- Run Button -->
                        <button @click="runTest" class="btn btn-primary" :disabled="loading">
                            <span x-show="!loading" x-text="results ? 'Retest' : 'Run Analysis'"></span>
                            <span x-show="loading">Analyzing...</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div x-show="error"
                class="mb-xl p-md bg-red-50 text-error border border-error/20 rounded-lg flex items-center gap-sm"
                x-cloak>
                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="error" class="font-medium"></span>
            </div>

            <!-- Results Section -->
            <div x-show="results" x-transition.opacity>

                <!-- Main Score (Performance) -->
                <div class="flex justify-center mb-xl">
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto mb-md flex items-center justify-center">
                            <!-- Circular Progress using SVG -->
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="#f3f4f6" stroke-width="8" fill="none" />
                                <circle cx="64" cy="64" r="56" :stroke="getScoreColor(results?.scores?.performance)"
                                    stroke-width="8" fill="none" stroke-dasharray="351.86"
                                    :stroke-dashoffset="351.86 - (351.86 * (results?.scores?.performance || 0) / 100)"
                                    class="transition-all duration-1000 ease-out" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-4xl font-bold text-black"
                                    x-text="Math.round(results?.scores?.performance || 0)"></span>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-black">Performance Score</h2>
                        <p class="text-sm text-muted">Core Web Vitals Assessment</p>
                    </div>
                </div>

                <!-- Secondary Scores -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-md mb-xl">
                    <template
                        x-for="(label, key) in {accessibility: 'Accessibility', best_practices: 'Best Practices', seo: 'SEO'}">
                        <div class="bg-white border border-border rounded-lg p-md flex items-center gap-md shadow-sm">
                            <div class="relative w-12 h-12 flex-shrink-0">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="24" cy="24" r="20" stroke="#f3f4f6" stroke-width="4" fill="none" />
                                    <circle cx="24" cy="24" r="20" :stroke="getScoreColor(results?.scores?.[key])"
                                        stroke-width="4" fill="none" stroke-dasharray="125.66"
                                        :stroke-dashoffset="125.66 - (125.66 * (results?.scores?.[key] || 0) / 100)"
                                        class="transition-all duration-1000 ease-out" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs font-bold text-black"
                                        x-text="Math.round(results?.scores?.[key] || 0)"></span>
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-black text-sm" x-text="label"></div>
                                <div class="text-xs text-muted">Score</div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Detailed Metrics Table -->
                <div class="card bg-white border border-border shadow-sm rounded-lg overflow-hidden">
                    <div class="px-lg py-md border-b border-border bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-black">Lab Data Metrics</h3>
                        <span class="text-xs font-mono uppercase px-sm py-xs bg-gray-200 rounded text-gray-600"
                            x-text="strategy"></span>
                    </div>
                    <div class="divide-y divide-border">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="p-lg flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div>
                                    <div class="flex items-center gap-sm">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <span class="font-medium text-black">Largest Contentful Paint</span>
                                        <span class="text-xs text-muted bg-gray-100 px-xs rounded">LCP</span>
                                    </div>
                                    <p class="text-xs text-muted mt-xs ml-4">Time when the largest content element is
                                        painted.</p>
                                </div>
                                <div class="text-xl font-bold text-black" x-text="results?.metrics?.lcp || '-'"></div>
                            </div>
                            <div
                                class="p-lg flex items-center justify-between border-t md:border-t-0 md:border-l border-border hover:bg-gray-50 transition-colors">
                                <div>
                                    <div class="flex items-center gap-sm">
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                        <span class="font-medium text-black">First Contentful Paint</span>
                                        <span class="text-xs text-muted bg-gray-100 px-xs rounded">FCP</span>
                                    </div>
                                    <p class="text-xs text-muted mt-xs ml-4">Time when the first text or image is
                                        painted.</p>
                                </div>
                                <div class="text-xl font-bold text-black" x-text="results?.metrics?.fcp || '-'"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="p-lg flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div>
                                    <div class="flex items-center gap-sm">
                                        <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                        <span class="font-medium text-black">Cumulative Layout Shift</span>
                                        <span class="text-xs text-muted bg-gray-100 px-xs rounded">CLS</span>
                                    </div>
                                    <p class="text-xs text-muted mt-xs ml-4">Movement of visible elements within the
                                        viewport.</p>
                                </div>
                                <div class="text-xl font-bold text-black" x-text="results?.metrics?.cls || '-'"></div>
                            </div>
                            <div
                                class="p-lg flex items-center justify-between border-t md:border-t-0 md:border-l border-border hover:bg-gray-50 transition-colors">
                                <div>
                                    <div class="flex items-center gap-sm">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                        <span class="font-medium text-black">Total Blocking Time</span>
                                        <span class="text-xs text-muted bg-gray-100 px-xs rounded">TBT</span>
                                    </div>
                                    <p class="text-xs text-muted mt-xs ml-4">Sum of all time periods where task length >
                                        50ms.</p>
                                </div>
                                <div class="text-xl font-bold text-black" x-text="results?.metrics?.tbt || '-'"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Empty State -->
            <div x-show="!results && !loading && !error" class="text-center py-2xl">
                <div
                    class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-md border border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-black">Ready to Analyze</h3>
                <p class="text-muted mt-xs">Select a strategy and run the test to see results.</p>
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