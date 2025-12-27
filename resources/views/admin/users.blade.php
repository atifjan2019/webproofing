<x-admin-layout>
    <style>
        .admin-hero {
            position: relative;
            padding: 2rem 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: var(--radius-2xl);
            margin-bottom: var(--spacing-xl);
            overflow: hidden;
        }

        .admin-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(238, 49, 79, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(34, 139, 230, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .admin-hero-content {
            position: relative;
            z-index: 1;
            padding: 0 2rem;
        }

        .admin-hero h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .admin-hero p {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: rgba(238, 49, 79, 0.2);
            border: 1px solid rgba(238, 49, 79, 0.3);
            color: #ff6b7a;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 9999px;
            margin-bottom: 1rem;
        }

        .admin-nav {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: var(--color-bg-secondary);
            padding: 0.375rem;
            border-radius: 0.75rem;
            width: fit-content;
        }

        .admin-nav-link {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover {
            color: var(--color-text-primary);
            background: rgba(0, 0, 0, 0.05);
        }

        .admin-nav-link.active {
            background: #000000;
            color: #ffffff;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            text-align: left;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-text-muted);
            border-bottom: 1px solid var(--color-border);
            background: var(--color-bg-secondary);
        }

        .admin-table td {
            padding: 1rem;
            font-size: 0.875rem;
            color: var(--color-text-primary);
            border-bottom: 1px solid var(--color-border);
        }

        .admin-table tr:hover td {
            background: var(--color-bg-secondary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(18, 184, 134, 0.1);
            color: #12b886;
        }

        .status-badge.trialing {
            background: rgba(34, 139, 230, 0.1);
            color: #228be6;
        }

        .status-badge.inactive {
            background: rgba(134, 142, 150, 0.1);
            color: #868e96;
        }

        .status-badge.canceled {
            background: rgba(250, 82, 82, 0.1);
            color: #fa5252;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn svg {
            width: 14px;
            height: 14px;
        }

        .action-btn.active {
            background: rgba(18, 184, 134, 0.1);
            color: #12b886;
        }

        .action-btn.active:hover {
            background: rgba(18, 184, 134, 0.2);
        }

        .action-btn.inactive {
            background: rgba(134, 142, 150, 0.1);
            color: #868e96;
        }

        .action-btn.inactive:hover {
            background: rgba(134, 142, 150, 0.2);
        }

        .action-btn.suspend {
            background: rgba(250, 176, 5, 0.1);
            color: #fab005;
        }

        .action-btn.suspend:hover {
            background: rgba(250, 176, 5, 0.2);
        }

        .action-btn.danger {
            background: rgba(250, 82, 82, 0.1);
            color: #fa5252;
        }

        .action-btn.danger:hover {
            background: rgba(250, 82, 82, 0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #000000;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .user-name.text-active {
            color: #12b886;
        }

        .user-name.text-suspended {
            color: #fa5252;
        }

        .user-name.text-super-admin {
            color: #fab005;
        }

        .user-email {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
        }

        .stripe-id {
            font-family: monospace;
            font-size: 0.75rem;
            background: var(--color-bg-secondary);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
    </style>

    <div class="container">
        <!-- Hero Section -->
        <div class="admin-hero animate-fadeInUp">
            <div class="admin-hero-content">
                <div class="admin-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Super Admin
                </div>
                <h1>All Users</h1>
                <p>Manage user accounts and subscriptions</p>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex items-center justify-between mb-xl animate-fadeInUp" style="animation-delay: 50ms;">
            <div class="admin-nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Overview</a>
                <a href="{{ route('admin.users') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">Users</a>
                <a href="{{ route('admin.sites') }}"
                    class="admin-nav-link {{ request()->routeIs('admin.sites') ? 'active' : '' }}">Websites</a>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create User
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-lg animate-fadeInUp">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="card card-body mb-lg animate-fadeInUp" style="animation-delay: 50ms;">
            <form action="{{ route('admin.users') }}" method="GET" class="flex gap-md">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search by name or email..." class="form-input w-full">
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
                @if($search)
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">Clear</a>
                @endif
            </form>
        </div>

        <!-- Users Table -->
        <div class="card animate-fadeInUp" style="animation-delay: 100ms;">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Signup Date</th>
                            <th>Sites</th>
                            <th>Subscription</th>
                            <th>Free Access</th>
                            <th>Stripe Customer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="user-details">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="user-name {{ $user->is_super_admin ? 'text-super-admin' : ($user->is_suspended ? 'text-suspended' : 'text-active') }}">
                                                {{ $user->name }}
                                                @if($user->is_super_admin)
                                                    <span class="status-badge active"
                                                        style="margin-left: 0.5rem; background: rgba(250, 176, 5, 0.1); color: #fab005;">Super
                                                        Admin</span>
                                                @else
                                                    <span class="status-badge"
                                                        style="margin-left: 0.5rem; background: rgba(34, 184, 207, 0.1); color: #22b8cf;">Customer</span>
                                                @endif
                                                @if($user->is_suspended)
                                                    <span class="status-badge canceled"
                                                        style="margin-left: 0.5rem;">Suspended</span>
                                                @else
                                                    <span class="status-badge active" style="margin-left: 0.5rem;">Active</span>
                                                @endif

                                                @if($user->has_free_access)
                                                    <span class="status-badge trialing" style="margin-left: 0.5rem;">Free</span>
                                                @endif
                                            </a>
                                            <span class="user-email">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>{{ $user->sites_count }}</td>
                                <td>
                                    @if($user->subscription)
                                        <span class="status-badge {{ $user->subscription->status }}">
                                            {{ ucfirst($user->subscription->status) }}
                                        </span>
                                    @else
                                        <span class="status-badge inactive">No Subscription</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.toggleFreeAccess', $user) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="action-btn {{ $user->has_free_access ? 'active' : 'inactive' }}">
                                            @if($user->has_free_access)
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Enabled
                                            @else
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Disabled
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($user->stripe_customer_id)
                                        <code class="stripe-id">{{ $user->stripe_customer_id }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-xs">
                                        @if(!$user->is_super_admin)
                                            @if($user->is_suspended)
                                                <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn active" title="Unsuspend user">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Activate
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn suspend" title="Suspend user">
                                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                        Suspend
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-muted text-xs">Protected</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-xl">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-lg p-md border-t">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>