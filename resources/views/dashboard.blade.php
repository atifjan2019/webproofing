<x-app-layout>
    <div class="container">
        <!-- Hero Section -->
        <div class="mb-2xl animate-fadeInUp">
            <div class="flex flex-col gap-lg" style="flex-direction: column;">
                <div>
                    <h1 class="text-4xl font-bold text-black mb-sm">
                        Welcome back, <span class="text-accent">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    </h1>
                    <p class="text-secondary text-lg">
                        Monitor your digital infrastructure at a glance
                    </p>
                </div>
                <div>
                    <a href="{{ route('sites.create') }}" class="btn btn-primary btn-lg">
                        <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Website
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg-grid-cols-4 gap-lg mb-2xl">
            <!-- Total Sites -->
            <div class="stat-card animate-fadeInUp">
                <div class="flex items-center justify-between mb-lg">
                    <div class="stat-icon accent">
                        <svg style="color: var(--color-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <span class="badge badge-info">Total</span>
                </div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Sites Monitored</div>
            </div>

            <!-- Active Sites -->
            <div class="stat-card animate-fadeInUp" style="animation-delay: 100ms;">
                <div class="flex items-center justify-between mb-lg">
                    <div class="stat-icon success">
                        <svg style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="badge badge-success">Active</span>
                </div>
                <div class="stat-value">{{ $stats['active'] }}</div>
                <div class="stat-label">Healthy Nodes</div>
            </div>

            <!-- Issues -->
            <div class="stat-card animate-fadeInUp" style="animation-delay: 200ms;">
                <div class="flex items-center justify-between mb-lg">
                    <div class="stat-icon warning">
                        <svg style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="badge badge-warning">Paused</span>
                </div>
                <div class="stat-value">{{ $stats['paused'] ?? 0 }}</div>
                <div class="stat-label">Need Attention</div>
            </div>

            <!-- Trial -->
            <div class="stat-card animate-fadeInUp" style="animation-delay: 300ms;">
                <div class="flex items-center justify-between mb-lg">
                    <div class="stat-icon info">
                        <svg style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="badge badge-default">Trial</span>
                </div>
                <div class="stat-value">{{ $stats['on_trial'] }}</div>
                <div class="stat-label">Trial Period</div>
            </div>
        </div>

        <!-- Recent Sites Section -->
        <div>
            <div class="flex items-center justify-between mb-lg">
                <h2 class="text-2xl font-bold text-black">Your Websites</h2>
                @if($sites->isNotEmpty())
                    <a href="{{ route('sites.create') }}" class="btn btn-primary btn-sm">
                        <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Site
                    </a>
                @endif
            </div>

            @if($sites->isEmpty())
                <!-- Empty State -->
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="empty-title">No websites yet</h3>
                        <p class="empty-text">
                            Start monitoring your digital properties by adding your first website to the system.
                        </p>
                        <a href="{{ route('sites.create') }}" class="btn btn-primary btn-lg">
                            <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Your First Website
                        </a>
                    </div>
                </div>
            @else
                <!-- Sites List -->
                <div class="flex flex-col gap-md">
                    @foreach($sites as $index => $site)
                        <a href="{{ route('sites.show', $site) }}" class="site-card animate-fadeInUp"
                            style="animation-delay: {{ $index * 50 }}ms;">
                            <div class="site-info">
                                <div class="site-avatar">
                                    {{ strtoupper(substr($site->domain, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="site-domain">{{ $site->domain }}</h4>
                                    <div class="site-meta">
                                        <span>{{ $site->created_at->format('M d, Y') }}</span>
                                        <span class="site-meta-dot"></span>
                                        @php $status = $site->trial_status; @endphp
                                        <span
                                            class="@if(in_array($status['status'], ['subscribed', 'subscribed_trial'])) text-success @elseif($status['status'] === 'trial') text-success @elseif($status['status'] === 'expired') text-danger @elseif($status['status'] === 'paused') text-warning @else text-muted @endif font-medium">
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="site-actions">
                                <span class="site-action-btn">
                                    Manage
                                    <svg class="icon-md inline-block ml-md" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($sites->hasPages())
                    <div class="mt-xl">
                        {{ $sites->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</x-app-layout>