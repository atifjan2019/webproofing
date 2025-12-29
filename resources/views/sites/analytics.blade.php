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

                    <div style="height: 300px; position: relative;" x-show="dailyData && dailyData.length > 0">
                        <!-- Canvas for Chart.js -->
                        <canvas id="dailyChart"></canvas>
                    </div>

                    <div x-show="!dailyData || dailyData.length === 0" class="flex items-center justify-center"
                        style="height: 300px;">
                        <p class="text-muted">No activity data available for this period.</p>
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
                <section class="card" x-show="hasGsc">
                    <div class="card-body">
                        <div class="flex flex-col lg-flex-row lg-items-center justify-between gap-md mb-lg">
                            <div>
                                <h3 class="font-semibold text-black">Search Performance</h3>
                                <p class="text-sm text-secondary">Analyze top queries using RegEx filters.</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-sm">
                                <div class="relative">
                                    <select x-model="gscFilterPreset" @change="handlePresetChange"
                                        class="form-select text-sm py-xs pl-sm pr-lg"
                                        style="min-width: 160px; height: 38px; border-radius: 8px; border: 1px solid var(--color-border); background: #fff;">
                                        <option value="none">All Queries</option>
                                        <option value="questions">Questions</option>
                                        <option value="longtail">Long-Tail (5+ words)</option>
                                        <option value="brand">Brand Queries</option>
                                        <option value="transactional">Transactional</option>
                                        <option value="informational">Informational</option>
                                        <option value="custom">Custom RegEx...</option>
                                    </select>
                                </div>

                                <div x-show="gscFilterPreset === 'custom' || gscFilterPreset === 'brand'"
                                    class="flex items-center gap-xs">
                                    <input type="text" x-model="gscFilterExpression" @keyup.enter="applyFilter"
                                        placeholder="Enter RegEx..." class="form-input text-sm py-xs px-sm"
                                        style="width: 200px; height: 38px; border-radius: 8px; border: 1px solid var(--color-border);">
                                </div>

                                <button @click="applyFilter" class="btn btn-secondary btn-sm" style="height: 38px;"
                                    :disabled="queriesLoading">
                                    <span x-show="!queriesLoading">Apply</span>
                                    <span x-show="queriesLoading" class="animate-spin">⟳</span>
                                </button>
                            </div>
                        </div>

                        <!-- Loading State for Queries -->
                        <div x-show="queriesLoading" class="text-center py-xl">
                            <div class="spinner border-t-accent w-8 h-8 rounded-full animate-spin mx-auto mb-md"></div>
                            <p class="text-xs text-muted">Updating queries...</p>
                        </div>

                        <div class="table-responsive" x-show="!queriesLoading && gscQueries && gscQueries.length > 0">
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
                                    <template x-for="(q, index) in paginatedQueries" :key="index">
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

                        <!-- Queries Pagination Controls -->
                        <div class="flex items-center justify-between mt-md"
                            x-show="!queriesLoading && gscQueries && gscQueries.length > queriesPageSize">
                            <div class="text-sm text-secondary">
                                Showing <span x-text="(queriesCurrentPage - 1) * queriesPageSize + 1"></span> to <span
                                    x-text="Math.min(queriesCurrentPage * queriesPageSize, gscQueries.length)"></span>
                                of <span x-text="gscQueries.length"></span>
                            </div>
                            <div class="flex gap-xs">
                                <button @click="prevQueriesPage" :disabled="queriesCurrentPage === 1"
                                    class="btn btn-secondary btn-sm"
                                    :class="{'opacity-50 cursor-not-allowed': queriesCurrentPage === 1}">Previous</button>
                                <button @click="nextQueriesPage" :disabled="queriesCurrentPage === queriesTotalPages"
                                    class="btn btn-secondary btn-sm"
                                    :class="{'opacity-50 cursor-not-allowed': queriesCurrentPage === queriesTotalPages}">Next</button>
                            </div>
                        </div>

                        <!-- Empty Queries State -->
                        <div x-show="!queriesLoading && (!gscQueries || gscQueries.length === 0)"
                            class="text-center py-xl">
                            <p class="text-muted">No queries found for this period/filter.</p>
                        </div>
                    </div>
                </section>

                <!-- Top Pages -->
                <section class="card" x-show="hasGsc">
                    <div class="card-body">
                        <h3 class="font-semibold text-black mb-lg">Top Pages</h3>

                        <div class="table-responsive" x-show="gscPages && gscPages.length > 0">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th @click="sortPages('page')" class="cursor-pointer hover:bg-gray-50">
                                            Page
                                            <span x-show="pagesSortCol === 'page'"
                                                x-text="pagesSortAsc ? '↑' : '↓'"></span>
                                        </th>
                                        <th class="text-right cursor-pointer hover:bg-gray-50"
                                            @click="sortPages('clicks')">
                                            Clicks
                                            <span x-show="pagesSortCol === 'clicks'"
                                                x-text="pagesSortAsc ? '↑' : '↓'"></span>
                                        </th>
                                        <th class="text-right cursor-pointer hover:bg-gray-50"
                                            @click="sortPages('impressions')">
                                            Impressions
                                            <span x-show="pagesSortCol === 'impressions'"
                                                x-text="pagesSortAsc ? '↑' : '↓'"></span>
                                        </th>
                                        <th class="text-right cursor-pointer hover:bg-gray-50"
                                            @click="sortPages('ctr')">
                                            CTR
                                            <span x-show="pagesSortCol === 'ctr'"
                                                x-text="pagesSortAsc ? '↑' : '↓'"></span>
                                        </th>
                                        <th class="text-right cursor-pointer hover:bg-gray-50"
                                            @click="sortPages('position')">
                                            Position
                                            <span x-show="pagesSortCol === 'position'"
                                                x-text="pagesSortAsc ? '↑' : '↓'"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(p, index) in paginatedPages" :key="index">
                                        <tr>
                                            <td class="truncate" style="max-width: 300px;" :title="p.page">
                                                <a :href="p.page" target="_blank" class="text-primary hover:underline"
                                                    x-text="p.page"></a>
                                            </td>
                                            <td class="text-right" x-text="formatNumber(p.clicks)"></td>
                                            <td class="text-right" x-text="formatNumber(p.impressions)"></td>
                                            <td class="text-right" x-text="formatPercent(p.ctr)"></td>
                                            <td class="text-right" x-text="p.position"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="flex items-center justify-between mt-md" x-show="sortedPages.length > pageSize">
                            <div class="text-sm text-secondary">
                                Showing <span x-text="(currentPage - 1) * pageSize + 1"></span> to <span
                                    x-text="Math.min(currentPage * pageSize, sortedPages.length)"></span> of <span
                                    x-text="sortedPages.length"></span>
                            </div>
                            <div class="flex gap-xs">
                                <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-secondary btn-sm"
                                    :class="{'opacity-50 cursor-not-allowed': currentPage === 1}">Previous</button>
                                <button @click="nextPage" :disabled="currentPage === totalPages"
                                    class="btn btn-secondary btn-sm"
                                    :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}">Next</button>
                            </div>
                        </div>

                        <!-- Empty Pages State -->
                        <div x-show="!gscPages || gscPages.length === 0" class="text-center py-xl">
                            <p class="text-muted">No pages found for this period.</p>
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
            // Internal non-reactive variable for chart instance
            let _chartInstance = null;

            return {
                loading: false,
                error: null,
                hasGa4: false,
                hasGsc: false,
                ga4: {},
                gsc: {},
                dateRange: {},
                cachedAt: null,
                dailyData: [],
                gscQueries: [],
                gscPages: [],

                // Pages Sorting & Pagination
                pagesSortCol: 'clicks',
                pagesSortAsc: false,
                currentPage: 1,
                pageSize: 10,

                // GSC Filter States
                gscFilterPreset: 'none',
                gscFilterExpression: '',
                lastFilter: { period: '7d', refresh: false },
                queriesLoading: false,
                queriesCurrentPage: 1,
                queriesPageSize: 10,

                init() {
                    // Listen for filter-changed events from the period filter
                    window.addEventListener('filter-changed', (e) => {
                        this.lastFilter = e.detail;
                        this.fetchData(this.lastFilter);
                    });
                },

                handlePresetChange() {
                    const domain = '{{ $site->domain }}'.split('.')[0];
                    if (this.gscFilterPreset === 'questions') {
                        this.gscFilterExpression = '^who|^what|^where|^when|^why|^how|^do|^can|^is|^are|^does';
                    } else if (this.gscFilterPreset === 'longtail') {
                        this.gscFilterExpression = '([^" "] + " "){4,}';
                    } else if (this.gscFilterPreset === 'brand') {
                        this.gscFilterExpression = domain;
                    } else if (this.gscFilterPreset === 'transactional') {
                        this.gscFilterExpression = 'buy|price|cost|shop|discount|order';
                    } else if (this.gscFilterPreset === 'informational') {
                        this.gscFilterExpression = 'how to|why|what is|guide|tutorial';
                    } else if (this.gscFilterPreset === 'none') {
                        this.gscFilterExpression = '';
                        this.applyFilter();
                        return;
                    }

                    // For custom, we don't automatically trigger refresh until they type something/apply
                    if (this.gscFilterPreset !== 'custom') {
                        this.applyFilter();
                    }
                },

                applyFilter() {
                    // For filtering only queries, passed 'queries' type
                    this.fetchData(this.lastFilter, 'queries');
                },

                async fetchData(filter, type = 'all') {
                    if (type === 'all') {
                        this.loading = true;
                        window.dispatchEvent(new CustomEvent('analytics-loading', { detail: true }));
                    } else if (type === 'queries') {
                        this.queriesLoading = true;
                    }

                    this.error = null;

                    try {
                        const url = new URL('{{ route("sites.metrics", $site) }}');
                        url.searchParams.append('period', filter.period);
                        if (filter.refresh) {
                            url.searchParams.append('refresh', '1');
                        }

                        // Pass type param
                        url.searchParams.append('type', type);

                        // Add GSC regex filter if present
                        if (this.gscFilterExpression) {
                            url.searchParams.append('gsc_regex', this.gscFilterExpression);
                        }

                        const res = await fetch(url);
                        if (!res.ok) throw new Error('API Error');

                        const data = await res.json();

                        if (type === 'all') {
                            this.ga4 = data.ga4 || {};
                            this.gsc = data.gsc || {};
                            this.hasGa4 = !!data.ga4;
                            this.hasGsc = !!data.gsc;
                            this.dateRange = data.date_range;
                            this.cachedAt = data.cached_at || null;

                            this.dailyData = data.daily || [];
                            this.gscQueries = data.gsc_queries || [];
                            // Reset pagination on new data fetch
                            this.queriesCurrentPage = 1;
                            this.currentPage = 1;

                            this.gscPages = data.gsc_pages || [];

                            // Wait for Alpine to update x-show visibility before rendering chart
                            this.$nextTick(() => {
                                this.renderChart(data.daily);
                            });
                        } else if (type === 'queries') {
                            // Only update queries
                            this.gscQueries = data.gsc_queries || [];
                            this.queriesCurrentPage = 1; // Reset queries pagination
                        }

                    } catch (e) {
                        console.error(e);
                        this.error = 'Failed to load data. Please try again.';
                    } finally {
                        if (type === 'all') {
                            this.loading = false;
                            window.dispatchEvent(new CustomEvent('analytics-loading', { detail: false }));
                        } else {
                            this.queriesLoading = false;
                        }
                    }
                },

                renderChart(dailyData) {
                    if (typeof Chart === 'undefined') {
                        console.error('Chart.js is not loaded');
                        return;
                    }

                    if (_chartInstance) {
                        _chartInstance.destroy();
                        _chartInstance = null;
                    }

                    if (!dailyData || dailyData.length === 0) return;

                    // Ensure element exists
                    const canvas = document.getElementById('dailyChart');
                    if (!canvas) return;

                    const ctx = canvas.getContext('2d');

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

                    // Store chart instance on the DOM element or as a non-reactive property
                    _chartInstance = new Chart(ctx, {
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
                },

                sortPages(col) {
                    if (this.pagesSortCol === col) {
                        this.pagesSortAsc = !this.pagesSortAsc;
                    } else {
                        this.pagesSortCol = col;
                        // Default sort direction based on column type
                        // Text columns (page) default to Asc
                        // Numeric columns (clicks, imp, ctr) default to Desc
                        this.pagesSortAsc = (col === 'page');
                    }
                },

                get sortedPages() {
                    return [...this.gscPages].sort((a, b) => {
                        let valA = a[this.pagesSortCol];
                        let valB = b[this.pagesSortCol];

                        if (typeof valA === 'string') {
                            valA = valA.toLowerCase();
                            valB = valB.toLowerCase();
                        }

                        if (valA < valB) return this.pagesSortAsc ? -1 : 1;
                        if (valA > valB) return this.pagesSortAsc ? 1 : -1;
                        return 0;
                    });
                },

                get paginatedPages() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    const end = start + this.pageSize;
                    return this.sortedPages.slice(start, end);
                },

                get totalPages() {
                    return Math.ceil(this.sortedPages.length / this.pageSize);
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                // Queries Pagination Logic
                get paginatedQueries() {
                    const start = (this.queriesCurrentPage - 1) * this.queriesPageSize;
                    const end = start + this.queriesPageSize;
                    return (this.gscQueries || []).slice(start, end);
                },

                get queriesTotalPages() {
                    return Math.ceil((this.gscQueries || []).length / this.queriesPageSize);
                },

                nextQueriesPage() {
                    if (this.queriesCurrentPage < this.queriesTotalPages) {
                        this.queriesCurrentPage++;
                    }
                },

                prevQueriesPage() {
                    if (this.queriesCurrentPage > 1) {
                        this.queriesCurrentPage--;
                    }
                }
            }
        }
    </script>
</x-app-layout>