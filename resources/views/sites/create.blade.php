<x-app-layout>
    <div class="container" style="max-width: 42rem;">
        <!-- Back Link -->
        <a href="{{ route('sites.index') }}" class="inline-flex items-center gap-sm text-muted mb-xl"
            style="transition: color 0.3s;">
            <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Websites
        </a>

        <!-- Main Card -->
        <div class="card card-body-lg">
            <!-- Header -->
            <div class="text-center mb-xl">
                <div class="empty-icon mb-lg" style="width: 4rem; height: 4rem; margin-left: auto; margin-right: auto;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 2rem; height: 2rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-black mb-sm">Add New Website</h1>
                <p class="text-secondary">Enter the domain you want to monitor</p>
            </div>

            <form method="POST" action="{{ route('sites.store') }}">
                @csrf

                <!-- Domain Input -->
                <div class="form-group">
                    <label for="url" class="form-label">Domain</label>
                    <div class="form-input-icon">
                        <svg class="icon icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <input type="text" name="url" id="url" value="{{ old('url') }}"
                            class="form-input @error('url') error @enderror" placeholder="example.com" autofocus>
                    </div>
                    @error('url')
                        <div class="form-error">
                            <svg class="icon-sm" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                    <p class="form-hint">
                        Examples: example.com, mysite.org, shop.example.com
                    </p>
                </div>

                <!-- Trial Info Card -->
                <div class="alert alert-info mb-xl" style="padding: var(--spacing-lg); align-items: flex-start;">
                    <div class="stat-icon accent flex-shrink-0" style="width: 3rem; height: 3rem;">
                        <svg fill="currentColor" viewBox="0 0 20 20"
                            style="width: 1.5rem; height: 1.5rem; color: var(--color-accent);">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-black mb-xs">7-Day Free Trial</h4>
                        <p class="text-sm text-secondary">
                            New domains get a free 7-day trial with full monitoring features including screenshots,
                            change detection, and Google services integration. Each domain can only use the trial once.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-md">
                    <a href="{{ route('sites.index') }}" class="btn btn-ghost">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Add Website
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>