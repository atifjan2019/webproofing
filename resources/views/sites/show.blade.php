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
            <div class="flex items-center gap-md">
                <span
                    class="badge @if($trialStatus['status'] === 'trial') badge-success @elseif($trialStatus['status'] === 'expired') badge-danger @elseif($trialStatus['status'] === 'paused') badge-warning @else badge-default @endif">
                    {{ $trialStatus['label'] }}
                </span>
                <a href="https://{{ $site->domain }}" target="_blank" class="btn btn-secondary btn-sm">
                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Visit Site
                </a>
            </div>
        </div>

        @include('sites.partials.nav')

        <!-- Analytics Section -->
        @if($site->hasGa4() || $site->hasGsc())
            <div class="mt-xl mb-xl" x-data="analyticsData()" x-init="fetchData()">
                <div class="flex items-center justify-between mb-lg mobile-stack-header">
                    <div class="flex items-center gap-sm">
                        <h2 class="text-xl font-bold text-black">Analytics Overview</h2>
                        <span class="tooltip-container">
                            <span class="tooltip-trigger">?</span>
                            <span class="tooltip-content tooltip-wide">Data may be delayed by up to 48 hours for Search
                                Console. Analytics data is typically near real-time.</span>
                        </span>
                    </div>
                    <span class="data-delay-notice">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Last 7 days (available data)
                    </span>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="card card-body text-center" style="padding: var(--spacing-2xl);">
                    <div class="empty-icon mb-lg"
                        style="width: 4rem; height: 4rem; margin: 0 auto; animation: pulse 2s infinite;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="width: 2rem; height: 2rem; color: var(--color-accent);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <p class="text-muted">Loading analytics data...</p>
                </div>

                <!-- Error State -->
                <div x-show="error && !loading" class="alert alert-error" x-cloak>
                    <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span x-text="error"></span>
                </div>

                <!-- Stats Cards -->
                <div x-show="!loading && !error" x-cloak>
                    @if($site->hasGa4())
                        <h3 class="text-lg font-semibold text-black mb-md flex items-center gap-sm">
                            <svg class="icon-md" style="color: var(--color-info);" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Google Analytics
                        </h3>
                        <div class="grid grid-cols-2 lg-grid-cols-4 gap-md mb-xl">
                            <div class="stat-card">
                                <div class="stat-icon info mb-md">
                                    <svg style="color: var(--color-info);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatNumber(ga4?.users || 0)">0</div>
                                <div class="stat-label">Users</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon success mb-md">
                                    <svg style="color: var(--color-success);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatNumber(ga4?.sessions || 0)">0</div>
                                <div class="stat-label">Sessions</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon accent mb-md">
                                    <svg style="color: var(--color-accent);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatNumber(ga4?.pageviews || 0)">0</div>
                                <div class="stat-label">Pageviews</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon warning mb-md">
                                    <svg style="color: var(--color-warning);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatPercent(ga4?.engagement_rate || 0)">0%</div>
                                <div class="stat-label">Engagement Rate</div>
                            </div>
                        </div>
                    @endif

                    @if($site->hasGsc())
                        <h3 class="text-lg font-semibold text-black mb-md flex items-center gap-sm">
                            <svg class="icon-md" style="color: var(--color-success);" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Search Console
                        </h3>
                        <div class="grid grid-cols-2 lg-grid-cols-4 gap-md mb-xl">
                            <div class="stat-card">
                                <div class="stat-icon success mb-md">
                                    <svg style="color: var(--color-success);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatNumber(gsc?.clicks || 0)">0</div>
                                <div class="stat-label">Clicks</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon info mb-md">
                                    <svg style="color: var(--color-info);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatNumber(gsc?.impressions || 0)">0</div>
                                <div class="stat-label">Impressions</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon accent mb-md">
                                    <svg style="color: var(--color-accent);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="formatPercent(gsc?.ctr || 0)">0%</div>
                                <div class="stat-label">CTR</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon warning mb-md">
                                    <svg style="color: var(--color-warning);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                    </svg>
                                </div>
                                <div class="stat-value" x-text="(gsc?.position || 0).toFixed(1)">0</div>
                                <div class="stat-label">Avg Position</div>
                            </div>
                        </div>
                    @endif

                    <!-- Daily Chart Data (JSON for future chart integration) -->
                    <div class="card card-body" x-show="daily && daily.length > 0">
                        <h4 class="font-semibold text-black mb-lg">Daily Trend (Last 28 Days)</h4>
                        <div class="overflow-auto">
                            <table style="width: 100%; border-collapse: collapse; font-size: var(--font-size-sm);">
                                <thead>
                                    <tr style="border-bottom: 1px solid var(--color-border);">
                                        <th
                                            style="text-align: left; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                            Date</th>
                                        @if($site->hasGa4())
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                Users</th>
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                Sessions</th>
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                Pageviews</th>
                                        @endif
                                        @if($site->hasGsc())
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                Clicks</th>
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                Impressions</th>
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                CTR</th>
                                            <th
                                                style="text-align: right; padding: var(--spacing-sm); color: var(--color-text-muted);">
                                                Position</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="row in daily.slice(-7)" :key="row.date">
                                        <tr style="border-bottom: 1px solid var(--color-border);">
                                            <td style="padding: var(--spacing-sm); color: var(--color-text-primary);"
                                                x-text="row.date"></td>
                                            @if($site->hasGa4())
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="formatNumber(row.ga_users || 0)"></td>
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="formatNumber(row.ga_sessions || 0)"></td>
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="formatNumber(row.ga_pageviews || 0)"></td>
                                            @endif
                                            @if($site->hasGsc())
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="formatNumber(row.gsc_clicks || 0)"></td>
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="formatNumber(row.gsc_impressions || 0)"></td>
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="formatPercent(row.gsc_ctr || 0)"></td>
                                                <td style="text-align: right; padding: var(--spacing-sm);"
                                                    x-text="(row.gsc_position || 0).toFixed(1)"></td>
                                            @endif
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- No Analytics Configured -->
            <div class="card card-body mt-xl text-center" style="padding: var(--spacing-2xl);">
                <div class="empty-icon mb-lg" style="width: 4rem; height: 4rem; margin: 0 auto;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 2rem; height: 2rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-black mb-sm">No Analytics Configured</h3>
                <p class="text-muted mb-lg">Connect your Google Analytics and Search Console to see performance data.</p>
                <a href="{{ route('sites.google', $site) }}" class="btn btn-primary">
                    Configure Google Services
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-xl mt-xl" style="grid-template-columns: 1fr;">
            <!-- Main Column -->
            <div class="lg-col-span-2" style="display: flex; flex-direction: column; gap: var(--spacing-xl);">
                <!-- Trial Status Card -->
                @if($trialStatus['status'] === 'trial')
                    <div class="card card-body"
                        style="background: var(--color-accent-lighter); border-color: var(--color-accent);">
                        <div class="flex flex-col gap-md"
                            style="flex-direction: row; justify-content: space-between; align-items: center;">
                            <div>
                                <h3 class="text-sm font-semibold text-accent uppercase tracking-wider mb-xs">Trial Period
                                </h3>
                                <p class="text-black font-medium">{{ $trialStatus['message'] }}</p>
                            </div>
                            <div class="text-center">
                                <span class="text-4xl font-bold text-black">{{ $trialStatus['remaining_days'] }}</span>
                                <span class="block text-xs text-muted uppercase tracking-wider">Days Left</span>
                            </div>
                        </div>
                        <div class="progress mt-lg">
                            <div class="progress-bar"
                                style="width: {{ max(0, (7 - $trialStatus['remaining_days']) / 7 * 100) }}%"></div>
                        </div>
                    </div>
                @endif

                <!-- Screenshots Section -->
                <section>
                    <div class="flex items-center justify-between mb-lg">
                        <h3 class="text-xl font-bold text-black">Recent Screenshots</h3>
                        <a href="{{ route('sites.screenshots', $site) }}"
                            class="flex items-center gap-sm text-accent font-medium text-sm">
                            View Gallery
                            <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    @if($site->screenshots->isEmpty())
                        <div class="card card-body text-center" style="padding: var(--spacing-2xl);">
                            <div class="empty-icon mb-lg"
                                style="width: 4rem; height: 4rem; margin-left: auto; margin-right: auto; background: var(--color-bg-tertiary);">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="width: 2rem; height: 2rem; color: var(--color-text-muted);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-muted mb-lg">No screenshots captured yet</p>
                            @if($trialStatus['can_monitor'])
                                <form action="{{ route('sites.screenshots.capture', $site) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        Capture Initial Screenshot
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm-grid-cols-4 gap-md">
                            @foreach($site->screenshots->take(4) as $screenshot)
                                <div class="screenshot-card"
                                    onclick="window.location='{{ route('sites.screenshots', $site) }}'">
                                    <div class="screenshot-image">
                                        <img src="{{ $screenshot->image_src }}" alt="Screenshot">
                                        <div class="screenshot-overlay">
                                            <span class="text-white text-xs font-medium">Click to view</span>
                                        </div>
                                    </div>
                                    <div class="screenshot-meta">
                                        <span class="screenshot-date">{{ $screenshot->device_type }}</span>
                                        <span class="screenshot-device">{{ $screenshot->captured_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <!-- Sidebar -->
            <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);">
                <!-- Site Info Card -->
                <div class="card card-body">
                    <h3 class="text-sm font-semibold text-muted uppercase tracking-wider mb-lg">Site Information</h3>
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);">
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Domain</label>
                            <p class="text-black font-medium mt-xs">{{ $site->domain }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Added On</label>
                            <p class="text-black font-medium mt-xs">{{ $site->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Status</label>
                            <div class="flex items-center gap-sm mt-sm">
                                <span
                                    class="status-dot @if($site->status === 'active') active @else inactive @endif"></span>
                                <span class="text-black font-medium"
                                    style="text-transform: capitalize;">{{ $site->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                    <a href="{{ route('sites.google', $site) }}" class="btn btn-secondary justify-between"
                        style="justify-content: space-between;">
                        <div class="flex items-center gap-md">
                            <div class="stat-icon info" style="width: 2.5rem; height: 2.5rem;">
                                <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-info);"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="currentColor"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                </svg>
                            </div>
                            <span class="text-black font-medium">Google Services</span>
                        </div>
                        <svg class="icon-md text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Danger Zone -->
                <div class="pt-lg border-t">
                    <form action="{{ route('sites.destroy', $site) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this site?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-full">
                            Delete Website
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function analyticsData() {
            return {
                loading: true,
                error: null,
                ga4: null,
                gsc: null,
                daily: [],

                async fetchData() {
                    try {
                        const response = await fetch('{{ route("sites.metrics", $site) }}?period=7d');
                        if (!response.ok) {
                            throw new Error('Failed to fetch analytics data');
                        }
                        const data = await response.json();
                        this.ga4 = data.ga4;
                        this.gsc = data.gsc;
                        this.daily = data.daily || [];
                    } catch (e) {
                        this.error = e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                formatNumber(num) {
                    if (num >= 1000000) {
                        return (num / 1000000).toFixed(1) + 'M';
                    } else if (num >= 1000) {
                        return (num / 1000).toFixed(1) + 'K';
                    }
                    return num.toLocaleString();
                },

                formatPercent(num) {
                    return (num * 100).toFixed(1) + '%';
                }
            };
        }
    </script>

    <style>
        @media (min-width: 1024px) {
            .container>.grid {
                grid-template-columns: 2fr 1fr;
            }
        }
    </style>
</x-app-layout>