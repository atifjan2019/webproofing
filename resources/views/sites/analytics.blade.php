<x-app-layout>
    <div class="container">
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

            <div class="flex flex-wrap items-center justify-between gap-md mobile-stack-header" x-data="dateFilter()"
                x-init="init()">
                <!-- Period Filter - Segmented Control -->
                <div class="period-filter-group">
                    <button type="button" @click="setPeriod('24h')" class="period-filter-btn"
                        :class="currentPeriod === '24h' ? 'active' : ''">
                        <svg x-show="currentPeriod === '24h'" class="filter-check" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span>24h</span>
                    </button>
                    <button type="button" @click="setPeriod('7d')" class="period-filter-btn"
                        :class="currentPeriod === '7d' ? 'active' : ''">
                        <svg x-show="currentPeriod === '7d'" class="filter-check" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span>7 days</span>
                    </button>
                    <button type="button" @click="setPeriod('28d')" class="period-filter-btn"
                        :class="currentPeriod === '28d' ? 'active' : ''">
                        <svg x-show="currentPeriod === '28d'" class="filter-check" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span>28 days</span>
                    </button>
                    <button type="button" @click="setPeriod('90d')" class="period-filter-btn"
                        :class="currentPeriod === '90d' ? 'active' : ''">
                        <svg x-show="currentPeriod === '90d'" class="filter-check" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span>3 months</span>
                    </button>
                </div>

                <!-- Refresh Button -->
                <button @click="refreshData" class="btn btn-primary btn-full-mobile" :disabled="loading">
                    <svg class="icon-md" :class="{'animate-spin': loading}" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span x-text="loading ? 'Refreshing...' : 'Refresh Data'"></span>
                </button>
            </div>

        </div>

        @include('sites.partials.nav')



        <!-- Analytics Content -->
        <div class="mt-xl" x-data="analyticsDashboard()" @filter-changed.window="fetchData($event.detail)">

            <!-- Loading -->
            <div x-show="loading" class="card card-body text-center py-2xl">
                <div class="spinner border-t-accent w-12 h-12 rounded-full animate-spin mx-auto mb-lg"></div>
                <p class="text-muted">Loading analytics data...</p>
            </div>

            <!-- Error -->
            <div x-show="error" class="alert alert-error mb-xl" x-cloak>
                <div class="flex gap-md">
                    <svg class="icon-md mt-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-bold">Error loading data</h4>
                        <p class="text-sm" x-text="error"></p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div x-show="!loading && !error" x-cloak class="flex flex-col gap-xl">

                <!-- Date Range Info -->
                <div class="flex justify-between items-end border-b pb-md">
                    <div>
                        <h2 class="text-xl font-bold text-black">Performance Overview</h2>
                        <div class="flex items-center gap-sm mt-xs text-sm text-secondary">
                            <span x-text="formatDateRange(dateRange)"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-sm text-sm text-muted">
                        <span x-show="cachedAt" class="text-xs">
                            Updated: <span x-text="formatTime(cachedAt)"></span>
                        </span>
                        <span>Data from Google Analytics 4 & Search Console</span>
                    </div>
                </div>

                <!-- GA4 Stats -->
                <template x-if="hasGa4">
                    <section>
                        <h3 class="text-lg font-semibold text-black mb-md flex items-center gap-sm">
                            <svg class="icon-md text-info" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Traffic & Engagement
                        </h3>
                        <div class="grid grid-cols-2 lg-grid-cols-4 gap-md">
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Users</div>
                                <div class="text-2xl font-bold text-black" x-text="formatNumber(ga4.users)"></div>
                            </div>
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Sessions</div>
                                <div class="text-2xl font-bold text-black" x-text="formatNumber(ga4.sessions)"></div>
                            </div>
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Pageviews</div>
                                <div class="text-2xl font-bold text-black" x-text="formatNumber(ga4.pageviews)"></div>
                            </div>
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Engagement Rate</div>
                                <div class="text-2xl font-bold text-black"
                                    x-text="formatPercent(ga4.engagement_rate || 0)">
                                </div>
                            </div>
                        </div>
                    </section>
                </template>

                <!-- GSC Stats -->
                <template x-if="hasGsc">
                    <section>
                        <h3 class="text-lg font-semibold text-black mb-md flex items-center gap-sm">
                            <svg class="icon-md text-success" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search Performance
                        </h3>
                        <div class="grid grid-cols-2 lg-grid-cols-4 gap-md">
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Clicks</div>
                                <div class="text-2xl font-bold text-black" x-text="formatNumber(gsc.clicks)"></div>
                            </div>
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Impressions</div>
                                <div class="text-2xl font-bold text-black" x-text="formatNumber(gsc.impressions)"></div>
                            </div>
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">CTR</div>
                                <div class="text-2xl font-bold text-black" x-text="formatPercent(gsc.ctr || 0)"></div>
                            </div>
                            <div class="stat-card">
                                <div class="text-sm text-muted mb-xs">Avg Position</div>
                                <div class="text-2xl font-bold text-black" x-text="formatNumber(gsc.position, 1)"></div>
                            </div>
                        </div>
                    </section>
                </template>

                <!-- Daily Chart (Using simple bars for now) -->
                <section class="card card-body">
                    <h3 class="font-semibold text-black mb-lg">Daily Activity</h3>
                    <div style="height: 300px; position: relative;">
                        <!-- Canvas for Chart.js -->
                        <canvas id="dailyChart"></canvas>
                    </div>
                </section>

                <!-- Daily Data Table -->
                <section class="card" x-show="dailyData && dailyData.length > 0">
                    <div class="card-body">
                        <h3 class="font-semibold text-black mb-lg">Last 7 Days Overview</h3>

                        <!-- Desktop Table - Hidden on Mobile -->
                        <div class="table-responsive hide-on-mobile">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-right">Users</th>
                                        <th class="text-right">Sessions</th>
                                        <th class="text-right">Pageviews</th>
                                        <th class="text-right">Clicks</th>
                                        <th class="text-right">Impressions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in getLast7DaysData()" :key="row.date">
                                        <tr>
                                            <td>
                                                <span class="font-medium" x-text="formatTableDate(row.date)"></span>
                                            </td>
                                            <td class="text-right" x-text="formatNumber(row.ga_users || 0)"></td>
                                            <td class="text-right" x-text="formatNumber(row.ga_sessions || 0)"></td>
                                            <td class="text-right" x-text="formatNumber(row.ga_pageviews || 0)"></td>
                                            <td class="text-right" x-text="formatNumber(row.gsc_clicks || 0)"></td>
                                            <td class="text-right" x-text="formatNumber(row.gsc_impressions || 0)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card Stack -->
                        <div class="mobile-card-stack show-on-mobile" style="display: none;">
                            <template x-for="row in getLast7DaysData()" :key="row.date + '-mobile'">
                                <div class="mobile-stack-card">
                                    <div class="mobile-stack-row"
                                        style="border-bottom: none; padding-bottom: var(--spacing-sm);">
                                        <span class="font-semibold text-black"
                                            x-text="formatTableDate(row.date)"></span>
                                    </div>
                                    <div class="grid mobile-grid-2 gap-sm" style="margin-top: var(--spacing-sm);">
                                        <div class="mobile-stack-row"
                                            style="flex-direction: column; align-items: flex-start; gap: 2px;">
                                            <span class="mobile-stack-label">Users</span>
                                            <span class="mobile-stack-value"
                                                x-text="formatNumber(row.ga_users || 0)"></span>
                                        </div>
                                        <div class="mobile-stack-row"
                                            style="flex-direction: column; align-items: flex-start; gap: 2px;">
                                            <span class="mobile-stack-label">Sessions</span>
                                            <span class="mobile-stack-value"
                                                x-text="formatNumber(row.ga_sessions || 0)"></span>
                                        </div>
                                        <div class="mobile-stack-row"
                                            style="flex-direction: column; align-items: flex-start; gap: 2px;">
                                            <span class="mobile-stack-label">Clicks</span>
                                            <span class="mobile-stack-value"
                                                x-text="formatNumber(row.gsc_clicks || 0)"></span>
                                        </div>
                                        <div class="mobile-stack-row"
                                            style="flex-direction: column; align-items: flex-start; gap: 2px;">
                                            <span class="mobile-stack-label">Impressions</span>
                                            <span class="mobile-stack-value"
                                                x-text="formatNumber(row.gsc_impressions || 0)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>

                <!-- Top Search Queries -->
                <section class="card" x-show="gscQueries && gscQueries.length > 0">
                    <div class="card-body">
                        <h3 class="font-semibold text-black mb-lg">Top Search Queries</h3>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Query</th>
                                        <th class="text-right">Clicks</th>
                                        <th class="text-right">Impressions</th>
                                        <th class="text-right">CTR</th>
                                        <th class="text-right">Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(q, index) in gscQueries" :key="index">
                                        <tr>
                                            <td>
                                                <span class="font-medium" x-text="q.query"></span>
                                            </td>
                                            <td class="text-right" x-text="formatNumber(q.clicks)"></td>
                                            <td class="text-right" x-text="formatNumber(q.impressions)"></td>
                                            <td class="text-right" x-text="formatPercent(q.ctr)"></td>
                                            <td class="text-right" x-text="q.position"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function dateFilter() {
            return {
                currentPeriod: '7d',
                loading: false, // shared state? No, this is separate x-data.
                periods: [
                    { value: '24h', label: '24 Hours' },
                    { value: 'today', label: 'Today' },
                    { value: 'yesterday', label: 'Yesterday' },
                    { value: '2d', label: 'Last 2 Days' },
                    { value: '3d', label: 'Last 3 Days' },
                    { value: '7d', label: '7 Days' },
                    { value: '28d', label: '28 Days' },
                    { value: '30d', label: '30 Days' },
                    { value: '90d', label: '3 Months' },
                ],
                init() {
                    this.$nextTick(() => {
                        this.dispatchFilter();
                    });

                    // Listen for loading 
                    window.addEventListener('analytics-loading', (e) => {
                        this.loading = e.detail;
                    });
                },
                setPeriod(val) {
                    this.currentPeriod = val;
                    this.dispatchFilter();
                },
                refreshData() {
                    this.dispatchFilter(true);
                },
                dispatchFilter(refresh = false) {
                    window.dispatchEvent(new CustomEvent('filter-changed', {
                        detail: {
                            period: this.currentPeriod,
                            refresh: refresh
                        }
                    }));
                }
            }
        }

        function analyticsDashboard() {
            return {
                loading: false,
                error: null,
                hasGa4: false,
                hasGsc: false,
                ga4: {},
                gsc: {},
                dateRange: {},
                cachedAt: null,
                chartInstance: null,
                dailyData: [],
                gscQueries: [],

                async fetchData(filter) {
                    this.loading = true;
                    // Notify filter about loading state
                    window.dispatchEvent(new CustomEvent('analytics-loading', { detail: true }));

                    this.error = null;

                    try {
                        const url = new URL('{{ route("sites.metrics", $site) }}');
                        url.searchParams.append('period', filter.period);
                        if (filter.refresh) {
                            url.searchParams.append('refresh', '1');
                        }

                        const res = await fetch(url);
                        if (!res.ok) throw new Error('API Error');

                        const data = await res.json();

                        this.ga4 = data.ga4 || {};
                        this.gsc = data.gsc || {};
                        this.hasGa4 = !!data.ga4;
                        this.hasGsc = !!data.gsc;
                        this.dateRange = data.date_range;
                        this.cachedAt = data.cached_at || null;

                        // Store daily data and render chart
                        this.dailyData = data.daily || [];
                        this.gscQueries = data.gsc_queries || [];
                        this.renderChart(data.daily);

                    } catch (e) {
                        console.error(e);
                        this.error = 'Failed to load data. Please try again.';
                    } finally {
                        this.loading = false;
                        window.dispatchEvent(new CustomEvent('analytics-loading', { detail: false }));
                    }
                },

                renderChart(dailyData) {
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    if (!dailyData || dailyData.length === 0) return;

                    const ctx = document.getElementById('dailyChart').getContext('2d');

                    // Datasets
                    const datasets = [];

                    if (this.hasGa4) {
                        datasets.push({
                            label: 'Users',
                            data: dailyData.map(d => d.ga_users || 0),
                            borderColor: '#3B82F6', // Info color
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            yAxisID: 'y',
                            tension: 0.3
                        });
                    }

                    if (this.hasGsc) {
                        datasets.push({
                            label: 'Clicks',
                            data: dailyData.map(d => d.gsc_clicks || 0),
                            borderColor: '#10B981', // Success color
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            yAxisID: 'y',
                            tension: 0.3
                        });
                    }

                    this.chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: dailyData.map(d => {
                                const date = new Date(d.date);
                                return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
                            }),
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#E5E7EB'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                },

                formatNumber(val, decimals = 0) {
                    return Number(val || 0).toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                },

                formatPercent(val) {
                    return (val * 100).toFixed(1) + '%';
                },

                formatDiff(diff, isPercent = false) {
                    const prefix = diff.change > 0 ? '+' : '';
                    if (isPercent) {
                        // For rates (e.g. CTR), change is absolute percentage points usually, but here diff logic returns percentage change of the value.
                        // Let's stick to % change of the value
                        return `${prefix}${diff.pct.toFixed(1)}%`;
                    }
                    return `${prefix}${this.formatNumber(diff.change)} (${prefix}${diff.pct.toFixed(1)}%)`;
                },

                getColor(pct) {
                    if (pct > 0) return 'text-success';
                    if (pct < 0) return 'text-danger';
                    return 'text-muted';
                },

                formatDateRange(range) {
                    if (!range || !range.start) return '';
                    const start = new Date(range.start).toLocaleDateString();
                    const end = new Date(range.end).toLocaleDateString();
                    if (start === end) return start;
                    return `${start} - ${end}`;
                },

                formatTime(isoString) {
                    if (!isoString) return '';
                    const date = new Date(isoString);
                    return date.toLocaleString(undefined, {
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                getLast7DaysData() {
                    if (!this.dailyData || this.dailyData.length === 0) return [];
                    // Sort by date descending (newest first) and take last 7
                    return [...this.dailyData]
                        .sort((a, b) => new Date(b.date) - new Date(a.date))
                        .slice(0, 7);
                },

                formatTableDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    const today = new Date();
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);

                    if (date.toDateString() === today.toDateString()) {
                        return 'Today';
                    }
                    if (date.toDateString() === yesterday.toDateString()) {
                        return 'Yesterday';
                    }
                    return date.toLocaleDateString(undefined, {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric'
                    });
                }
            }
        }
    </script>
</x-app-layout>