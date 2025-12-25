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
                    <h1 class="text-2xl font-bold text-black">Visual History</h1>
                    <p class="text-muted text-sm mt-xs">7-day automated captures for {{ $site->domain }}</p>
                </div>
            </div>
            @if($trialStatus['can_monitor'])
                <form action="{{ route('sites.screenshots.capture', $site) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Request Capture
                    </button>
                </form>
            @endif
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

        <div class="mt-xl">
            @if ($screenshots->isEmpty())
                <!-- Empty State -->
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-icon" style="background: var(--color-bg-tertiary);">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="color: var(--color-text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="empty-title">No screenshots captured yet</h3>
                        <p class="empty-text">Start capturing screenshots to monitor your website's visual changes.</p>
                    </div>
                </div>
            @else
                <!-- Screenshots Grid -->
                <div class="grid grid-cols-2 sm-grid-cols-4 lg-grid-cols-7 gap-md">
                    @foreach ($screenshots as $index => $screenshot)
                        <a href="{{ $screenshot->image_src }}" target="_blank" class="screenshot-card animate-fadeInUp" style="animation-delay: {{ $index * 30 }}ms">
                            <div class="screenshot-image">
                                <img src="{{ $screenshot->image_src }}" alt="Screenshot">
                                <div class="screenshot-overlay">
                                    <span class="text-white text-xs font-medium">Open in new tab</span>
                                </div>
                            </div>
                            <div class="screenshot-meta">
                                <span class="screenshot-date">{{ $screenshot->captured_at?->format('D, M d') }}</span>
                                <div class="screenshot-info">
                                    <span class="screenshot-device">{{ $screenshot->device_type }}</span>
                                    @if($screenshot->load_time_ms)
                                        <span class="screenshot-time">{{ $screenshot->load_time_ms }}ms</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Retention Notice -->
                <div class="flex items-center justify-center gap-md mt-2xl text-muted">
                    <div
                        style="height: 1px; width: 4rem; background: linear-gradient(to right, transparent, var(--color-border));">
                    </div>
                    <span class="text-xs font-medium uppercase tracking-widest">Rolling 7-Day Retention</span>
                    <div
                        style="height: 1px; width: 4rem; background: linear-gradient(to left, transparent, var(--color-border));">
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>