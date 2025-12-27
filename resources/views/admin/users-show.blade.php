<x-admin-layout>
    <div class="container">
        <!-- Header -->
        <div class="flex items-center gap-md mb-xl">
            <a href="{{ route('admin.users') }}" class="btn btn-secondary flex items-center gap-sm">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Users
            </a>
            <h1 class="text-2xl font-bold">User Details: {{ $user->name }}</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info mb-lg">
                {{ session('info') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg-grid-cols-2 gap-xl">
            <!-- Main Info -->
            <div class="flex flex-col gap-lg">
                <!-- User Profile -->
                <div class="card card-body">
                    <h3 class="text-lg font-bold mb-md">Profile Information</h3>
                    <div class="grid grid-cols-2 gap-md">
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Name</label>
                            <p class="font-medium text-black">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Email</label>
                            <p class="font-medium text-black">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Joined</label>
                            <p class="font-medium text-black">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-muted uppercase tracking-wider">Status</label>
                            <div class="mt-xs">
                                @if($user->is_super_admin)
                                    <span class="badge badge-warning" style="background: #fab005; color: white;">Super
                                        Admin</span>
                                @else
                                    <span class="badge" style="background: #22b8cf; color: white;">Customer</span>
                                @endif
                                @if($user->is_suspended)
                                    <span class="badge badge-danger">Suspended</span>
                                @else
                                    <span class="badge badge-success">Active</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Permissions -->
                <div class="card card-body">
                    <h3 class="text-lg font-bold mb-md">Service Access Control</h3>
                    <form action="{{ route('admin.users.updateServices', $user) }}" method="POST">
                        @csrf
                        <div class="flex flex-col gap-md mb-lg">
                            <label class="flex items-center gap-md cursor-pointer">
                                <input type="checkbox" name="service_speed_test" value="1" {{ $user->service_speed_test ? 'checked' : '' }} class="form-checkbox">
                                <div>
                                    <span class="font-medium text-black">Speed Test</span>
                                    <p class="text-sm text-muted">Allow access to PageSpeed Insights</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-md cursor-pointer">
                                <input type="checkbox" name="service_screenshots" value="1" {{ $user->service_screenshots ? 'checked' : '' }} class="form-checkbox">
                                <div>
                                    <span class="font-medium text-black">Screenshots</span>
                                    <p class="text-sm text-muted">Allow capturing site screenshots</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-md cursor-pointer">
                                <input type="checkbox" name="service_google" value="1" {{ $user->service_google ? 'checked' : '' }} class="form-checkbox">
                                <div>
                                    <span class="font-medium text-black">Google Services</span>
                                    <p class="text-sm text-muted">Allow connecting GA4 and GSC</p>
                                </div>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Services</button>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="flex flex-col gap-lg">
                <!-- Subscription / Trial -->
                <div class="card card-body">
                    <h3 class="text-lg font-bold mb-md">Subscription & Trial</h3>

                    <div class="mb-lg">
                        <label class="text-xs text-muted uppercase tracking-wider">Stripe Customer ID</label>
                        <p class="font-mono text-sm bg-gray-100 p-xs rounded mt-xs">
                            {{ $user->stripe_customer_id ?? 'N/A' }}
                        </p>
                    </div>

                    @php
                        $isOnTrial = false;
                        if ($user->subscription && $user->subscription->onTrial()) {
                            $isOnTrial = true;
                        }
                        // Check active site trials
                        $siteTrialsCount = $user->trialDomains()->where('is_expired', false)->where('trial_ends_at', '>', now())->count();
                        if ($siteTrialsCount > 0)
                            $isOnTrial = true;
                    @endphp

                    @if($isOnTrial)
                        <div class="alert alert-info mb-md">
                            <div class="flex items-center gap-sm">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>User has active trials.</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.users.endTrial', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-full"
                                onclick="return confirm('Are you sure you want to end all trials for this user?')">
                                End All Trials Immediately
                            </button>
                        </form>
                    @else
                        <div class="text-muted text-sm">No active trials.</div>
                    @endif
                </div>

                <!-- Sites List -->
                <div class="card card-body">
                    <h3 class="text-lg font-bold mb-md">Websites ({{ $user->sites->count() }})</h3>
                    @if($user->sites->count() > 0)
                        <div class="flex flex-col gap-sm">
                            @foreach($user->sites as $site)
                                <div class="flex items-center justify-between p-sm border rounded hover:bg-gray-50">
                                    <span class="font-medium text-sm">{{ $site->domain }}</span>
                                    <span
                                        class="badge {{ $site->status === 'active' ? 'badge-success' : 'badge-default' }} text-xs">
                                        {{ $site->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-sm">No websites added.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>