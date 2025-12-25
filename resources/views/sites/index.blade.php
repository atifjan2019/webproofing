<x-app-layout>
    <div class="container">
        <!-- Header -->
        <div class="flex flex-col gap-lg mb-xl" style="flex-direction: column;">
            <div>
                <h1 class="text-3xl font-bold text-black mb-sm">My Websites</h1>
                <p class="text-secondary">Manage and monitor all your digital properties</p>
            </div>
            <div>
                <a href="{{ route('sites.create') }}" class="btn btn-primary">
                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Website
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success animate-fadeInUp">
                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error animate-fadeInUp">
                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if ($sites->isEmpty())
            <!-- Empty State -->
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="empty-title">No websites yet</h3>
                    <p class="empty-text">
                        Get started by adding your first website to monitor. Track screenshots, changes, and more.
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
            <!-- Sites Grid -->
            <div class="sites-grid">
                @foreach ($sites as $site)
                    @php $status = $site->trial_status; @endphp
                    <div class="site-card-new animate-fadeInUp" style="animation-delay: {{ $loop->index * 50 }}ms">
                        <!-- Card Header -->
                        <a href="{{ route('sites.show', $site) }}" class="site-card-main">
                            <div class="site-card-icon">
                                {{ strtoupper(substr($site->domain, 0, 1)) }}
                            </div>
                            <div class="site-card-info">
                                <h3 class="site-card-domain">{{ $site->domain }}</h3>
                                @if($status['status'] === 'trial' && $status['remaining_days'] > 0)
                                    <span class="site-card-trial">{{ $status['remaining_days'] }} days left in trial</span>
                                @elseif($status['status'] === 'expired')
                                    <span class="site-card-expired">Trial expired</span>
                                @elseif($status['status'] === 'active')
                                    <span class="site-card-active">Active</span>
                                @endif
                            </div>
                            <svg class="site-card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <!-- Quick Actions -->
                        <div class="site-card-actions">
                            <a href="{{ route('sites.analytics', $site) }}" class="site-quick-action" title="Analytics">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </a>
                            <a href="{{ route('sites.screenshots', $site) }}" class="site-quick-action" title="Screenshots">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <form action="{{ route('sites.destroy', $site) }}" method="POST" class="site-delete-form"
                                onsubmit="return confirm('Are you sure you want to remove this site?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="site-quick-action danger" title="Delete">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>