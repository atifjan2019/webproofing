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

        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .admin-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .admin-stat-card {
            background: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .admin-stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .admin-stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-bg-secondary);
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .admin-stat-icon svg {
            width: 24px;
            height: 24px;
            color: var(--color-text-primary);
        }

        .admin-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--color-text-primary);
            line-height: 1;
        }

        .admin-stat-label {
            font-size: 0.875rem;
            color: var(--color-text-muted);
            margin-top: 0.5rem;
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
                <h1>Admin Dashboard</h1>
                <p>Manage users, websites, and subscriptions</p>
            </div>
        </div>

        <!-- Navigation -->
        <div class="admin-nav animate-fadeInUp" style="animation-delay: 50ms;">
            <a href="{{ route('admin.dashboard') }}"
                class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Overview</a>
            <a href="{{ route('admin.users') }}"
                class="admin-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">Users</a>
            <a href="{{ route('admin.sites') }}"
                class="admin-nav-link {{ request()->routeIs('admin.sites') ? 'active' : '' }}">Websites</a>
        </div>

        <!-- Stats Grid -->
        <div class="admin-stats-grid animate-fadeInUp" style="animation-delay: 100ms;">
            <div class="admin-stat-card">
                <div class="admin-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="admin-stat-value">{{ $stats['total_users'] }}</div>
                <div class="admin-stat-label">Total Users</div>
            </div>

            <div class="admin-stat-card">
                <div class="admin-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
                <div class="admin-stat-value">{{ $stats['total_sites'] }}</div>
                <div class="admin-stat-label">Total Websites</div>
            </div>

            <div class="admin-stat-card">
                <div class="admin-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="admin-stat-value">{{ $stats['active_trials'] }}</div>
                <div class="admin-stat-label">Active Trials</div>
            </div>

            <div class="admin-stat-card">
                <div class="admin-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="admin-stat-value">{{ $stats['paid_users'] }}</div>
                <div class="admin-stat-label">Paid Subscribers</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-stats-grid animate-fadeInUp" style="animation-delay: 150ms;">
            <a href="{{ route('admin.users.create') }}" class="admin-stat-card"
                style="text-decoration: none; cursor: pointer;">
                <div class="admin-stat-icon" style="background: rgba(18, 184, 134, 0.1);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #12b886;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="admin-stat-value" style="font-size: 1rem;">Create User</div>
                <div class="admin-stat-label">Add new account</div>
            </a>

            <a href="{{ route('admin.users') }}" class="admin-stat-card"
                style="text-decoration: none; cursor: pointer;">
                <div class="admin-stat-icon" style="background: rgba(34, 139, 230, 0.1);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #228be6;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                    </svg>
                </div>
                <div class="admin-stat-value" style="font-size: 1rem;">Manage Users</div>
                <div class="admin-stat-label">View all accounts</div>
            </a>

            <a href="{{ route('admin.sites') }}" class="admin-stat-card"
                style="text-decoration: none; cursor: pointer;">
                <div class="admin-stat-icon" style="background: rgba(250, 176, 5, 0.1);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #fab005;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9" />
                    </svg>
                </div>
                <div class="admin-stat-value" style="font-size: 1rem;">Manage Sites</div>
                <div class="admin-stat-label">View all websites</div>
            </a>

            <a href="{{ route('dashboard') }}" class="admin-stat-card" style="text-decoration: none; cursor: pointer;">
                <div class="admin-stat-icon" style="background: rgba(134, 142, 150, 0.1);">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #868e96;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </div>
                <div class="admin-stat-value" style="font-size: 1rem;">Customer View</div>
                <div class="admin-stat-label">Switch to app</div>
            </a>
        </div>
    </div>
</x-admin-layout>