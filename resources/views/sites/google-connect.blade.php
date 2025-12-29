<x-app-layout>
    <div class="container">
        <!-- Header -->
        <div class="flex flex-col gap-lg mb-lg" style="flex-direction: column;">
            <div class="flex items-center gap-md">
                <a href="{{ route('sites.show', $site) }}" class="btn btn-secondary btn-icon">
                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-black">Google Services</h1>
                    <p class="text-muted text-sm mt-xs">Connect analytics and search console for {{ $site->domain }}</p>
                </div>
            </div>
        </div>

        @include('sites.partials.nav')

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success mt-xl animate-fadeInUp">
                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mt-xl animate-fadeInUp">
                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Upgrade Banner -->
        @if(!$trialStatus['can_monitor'])
            <div class="alert alert-warning mt-xl" style="padding: var(--spacing-lg);">
                <div class="flex items-center gap-md flex-1">
                    <div class="stat-icon warning" style="width: 3rem; height: 3rem;">
                        <svg style="width: 1.5rem; height: 1.5rem; color: #b58105;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-black">Upgrade Required</h3>
                        <p class="text-secondary">{{ $trialStatus['message'] }}</p>
                    </div>
                    </div>
                </div>
                <form action="{{ route('billing.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Upgrade Now</button>
                </form>
            </div>
        @endif

        <div class="mt-xl">
            <div class="card card-body-lg">
                <!-- Google Logo Header -->
                <div class="text-center mb-xl">
                    <div class="empty-icon mb-lg"
                        style="width: 5rem; height: 5rem; margin-left: auto; margin-right: auto; background: var(--color-bg-secondary);">
                        <svg style="width: 2.5rem; height: 2.5rem;" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-black mb-sm">Connect Google Services</h2>
                    <p class="text-secondary" style="max-width: 28rem; margin: 0 auto;">
                        Link your Google Analytics and Search Console to get insights and monitor your site's
                        performance.
                    </p>
                </div>

                @if($hasGoogleAccount)
                    <!-- Connected State -->
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);">
                        <!-- Account Info -->
                        <div class="card card-body"
                            style="background: var(--color-success-light); border-color: var(--color-success);">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-md">
                                    <div class="stat-icon success" style="width: 3rem; height: 3rem;">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-success);" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-black">Google Account Connected</h4>
                                        <p class="text-sm text-secondary">{{ $googleAccount->email }}</p>
                                    </div>
                                </div>
                                <span class="badge badge-success">Connected</span>
                            </div>
                        </div>

                        @if(!$hasRefreshToken)
                            <!-- Reconnect Warning -->
                            <div class="alert alert-warning">
                                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Missing refresh token. Please reconnect to restore full functionality.</span>
                            </div>
                        @endif

                        <!-- GA4 Status -->
                        <div class="card card-body">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-md">
                                    <div class="stat-icon @if($site->hasGa4()) success @endif"
                                        style="width: 3rem; height: 3rem; @if(!$site->hasGa4()) background: var(--color-bg-tertiary); @endif">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: @if($site->hasGa4()) var(--color-success) @else var(--color-text-muted) @endif;"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-black">Google Analytics 4</h4>
                                        @if($site->hasGa4())
                                            <p class="text-sm text-secondary">
                                                {{ $site->ga4_property_name ?? $site->ga4_property_id }}</p>
                                        @else
                                            <p class="text-sm text-muted">Not configured</p>
                                        @endif
                                    </div>
                                </div>
                                @if($site->hasGa4())
                                    <span class="badge badge-success">Configured</span>
                                @endif
                            </div>
                        </div>

                        <!-- GSC Status -->
                        <div class="card card-body">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-md">
                                    <div class="stat-icon @if($site->hasGsc()) success @endif"
                                        style="width: 3rem; height: 3rem; @if(!$site->hasGsc()) background: var(--color-bg-tertiary); @endif">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: @if($site->hasGsc()) var(--color-success) @else var(--color-text-muted) @endif;"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-black">Search Console</h4>
                                        @if($site->hasGsc())
                                            <p class="text-sm text-secondary">{{ $site->gsc_site_url }}</p>
                                        @else
                                            <p class="text-sm text-muted">Not configured</p>
                                        @endif
                                    </div>
                                </div>
                                @if($site->hasGsc())
                                    <span class="badge badge-success">Configured</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        @if($trialStatus['can_monitor'])
                            <div class="flex items-center justify-between pt-lg border-t gap-md" style="flex-wrap: wrap;">
                                <a href="{{ route('sites.google.configure', $site) }}" class="btn btn-primary">
                                    @if($site->hasGa4() || $site->hasGsc())
                                        Edit Properties
                                    @else
                                        Configure Properties
                                    @endif
                                </a>
                                
                                <form action="{{ route('sites.google.refresh', $site) }}" method="POST" class="mr-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" title="Refresh connection data">
                                        <svg class="icon-sm" style="margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Refresh
                                    </button>
                                </form>

                                <div class="flex items-center gap-md">
                                    @if(!$hasRefreshToken)
                                        <a href="{{ route('google.connect') }}" class="btn btn-secondary">
                                            Reconnect Google
                                        </a>
                                    @endif
                                    <form action="{{ route('google.disconnect') }}" method="POST"
                                        onsubmit="return confirm('Disconnect Google services?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Disconnect</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Not Connected State -->
                    <div class="text-center">
                        <div class="flex items-center justify-center gap-xl mb-xl">
                            <div class="text-center">
                                <div class="stat-icon info mb-md"
                                    style="width: 4rem; height: 4rem; margin-left: auto; margin-right: auto;">
                                    <svg style="width: 2rem; height: 2rem; color: var(--color-info);" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-secondary">Analytics</p>
                            </div>
                            <span class="text-3xl text-muted">+</span>
                            <div class="text-center">
                                <div class="stat-icon success mb-md"
                                    style="width: 4rem; height: 4rem; margin-left: auto; margin-right: auto;">
                                    <svg style="width: 2rem; height: 2rem; color: var(--color-success);" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-secondary">Search Console</p>
                            </div>
                        </div>

                        @if($trialStatus['can_monitor'])
                            <a href="{{ route('google.connect') }}" class="btn btn-primary btn-lg">
                                <svg class="icon-lg" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="currentColor"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                </svg>
                                Connect with Google
                            </a>
                            <p class="mt-md text-sm text-muted">
                                We'll request read-only access to your Analytics and Search Console data.
                            </p>
                        @else
                            <p class="text-warning">Upgrade your plan to connect Google services.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>