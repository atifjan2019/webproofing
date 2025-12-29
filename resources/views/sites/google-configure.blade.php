<x-app-layout>
    <div class="container" style="max-width: 48rem;">
        <!-- Back Link -->
        <a href="{{ route('sites.google', $site) }}" class="inline-flex items-center gap-sm text-muted mb-xl">
            <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Google Services
        </a>

        <!-- Header -->
        <div class="mb-xl">
            <h1 class="text-2xl font-bold text-black mb-sm">Configure Google Properties</h1>
            <p class="text-secondary">Select the Analytics and Search Console properties for {{ $site->domain }}</p>
        </div>

        @include('sites.partials.nav')

        <!-- Flash Messages -->
        @if (session('error'))
            <div class="alert alert-error mt-lg animate-fadeInUp">
                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('sites.google.store', $site) }}" method="POST" class="mt-xl">
            @csrf

            <!-- GA4 Property Selection -->
            <div class="card card-body mb-lg">
                <div class="flex items-center gap-md mb-lg">
                    <div class="stat-icon info" style="width: 3rem; height: 3rem;">
                        <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-black">Google Analytics 4</h3>
                        <p class="text-sm text-muted">Select a GA4 property to track</p>
                    </div>
                </div>

                @if(count($ga4Properties) > 0)
                    <div class="form-group mb-0">
                        <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                            <label class="flex items-center gap-md p-md rounded-xl cursor-pointer" style="border: 1px solid var(--color-border); transition: all 0.2s;">
                                <input type="radio" name="ga4_property_id" value="" {{ !$site->ga4_property_id ? 'checked' : '' }} style="accent-color: var(--color-accent);">
                                <span class="text-secondary">None - Don't track analytics</span>
                            </label>
                            @foreach($ga4Properties as $property)
                                <label class="flex items-center gap-md p-md rounded-xl cursor-pointer" style="border: 1px solid var(--color-border); transition: all 0.2s;">
                                    <input type="radio" name="ga4_property_id" value="{{ $property['property_id'] }}" 
                                        data-name="{{ $property['property_name'] }}"
                                        {{ $site->ga4_property_id === $property['property_id'] ? 'checked' : '' }}
                                        style="accent-color: var(--color-accent);">
                                    <div>
                                        <span class="font-medium text-black">{{ $property['property_name'] }}</span>
                                        <span class="text-sm text-muted ml-md">{{ $property['account_name'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <input type="hidden" name="ga4_property_name" id="ga4_property_name" value="{{ $site->ga4_property_name }}">
                    </div>
                @else
                    <div class="empty-state-explained">

                        <h4>No GA4 properties found</h4>
                        <p>We couldn't find any Google Analytics 4 properties for your connected account.</p>
                        <ul class="empty-state-reasons">
                            <li>You may only have Universal Analytics (UA) properties - only GA4 is supported</li>
                            <li>You might have access via a different Google account</li>
                            <li>You may have viewer-only access which requires direct property invitation</li>
                        </ul>
                        
                    </div>
                @endif
            </div>

            <!-- GSC Property Selection -->
            <div class="card card-body mb-xl">
                <div class="flex items-center gap-md mb-lg">
                    <div class="stat-icon success" style="width: 3rem; height: 3rem;">
                        <svg style="width: 1.5rem; height: 1.5rem; color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-black">Search Console</h3>
                        <p class="text-sm text-muted">Select a verified property</p>
                    </div>
                </div>

                @if(count($gscProperties) > 0)
                    <div class="form-group mb-0">
                        <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                            <label class="flex items-center gap-md p-md rounded-xl cursor-pointer" style="border: 1px solid var(--color-border); transition: all 0.2s;">
                                <input type="radio" name="gsc_site_url" value="" {{ !$site->gsc_site_url ? 'checked' : '' }} style="accent-color: var(--color-accent);">
                                <span class="text-secondary">None - Don't track search data</span>
                            </label>
                            @foreach($gscProperties as $property)
                                <label class="flex items-center gap-md p-md rounded-xl cursor-pointer" style="border: 1px solid var(--color-border); transition: all 0.2s;">
                                    <input type="radio" name="gsc_site_url" value="{{ $property['site_url'] }}" 
                                        {{ $site->gsc_site_url === $property['site_url'] ? 'checked' : '' }}
                                        style="accent-color: var(--color-accent);">
                                    <div class="flex items-center gap-md">
                                        <span class="font-medium text-black">{{ $property['site_url'] }}</span>
                                        <span class="badge badge-default">{{ $property['permission_level'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span>No Search Console properties found. Make sure you have verified properties.</span>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-md">
                <a href="{{ route('sites.google', $site) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Configuration</button>
            </div>
        </form>
    </div>

    <script>
        // Update hidden property name field when GA4 selection changes
        document.querySelectorAll('input[name="ga4_property_id"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const name = this.dataset.name || '';
                document.getElementById('ga4_property_name').value = name;
            });
        });
    </script>
</x-app-layout>
