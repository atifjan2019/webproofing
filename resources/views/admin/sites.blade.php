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

        .status-badge.paused {
            background: rgba(250, 176, 5, 0.1);
            color: #fab005;
        }

        .status-badge.trial {
            background: rgba(34, 139, 230, 0.1);
            color: #228be6;
        }

        .status-badge.expired {
            background: rgba(250, 82, 82, 0.1);
            color: #fa5252;
        }

        .site-domain {
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .site-owner {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
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

        .action-btn.pause {
            background: rgba(250, 176, 5, 0.1);
            color: #fab005;
        }

        .action-btn.pause:hover {
            background: rgba(250, 176, 5, 0.2);
        }

        .action-btn.resume {
            background: rgba(18, 184, 134, 0.1);
            color: #12b886;
        }

        .action-btn.resume:hover {
            background: rgba(18, 184, 134, 0.2);
        }

        .action-btn svg {
            width: 14px;
            height: 14px;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(18, 184, 134, 0.1);
            color: #12b886;
            border: 1px solid rgba(18, 184, 134, 0.2);
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
                <h1>All Websites</h1>
                <p>Manage monitored websites and their status</p>
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

        @if(session('success'))
            <div class="alert alert-success mb-lg animate-fadeInUp">
                {{ session('success') }}
            </div>
        @endif

        <!-- Sites Table -->
        <div class="card animate-fadeInUp" style="animation-delay: 100ms;">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Owner</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Trial Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sites as $site)
                            @php
                                $trialStatus = $site->trial_status ?? ['status' => 'none', 'label' => 'N/A'];
                            @endphp
                            <tr>
                                <td>
                                    <span class="site-domain">{{ $site->domain }}</span>
                                </td>
                                <td>
                                    <span class="site-owner">{{ $site->user->email ?? 'Unknown' }}</span>
                                </td>
                                <td>{{ $site->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $site->status }}">
                                        {{ ucfirst($site->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $trialStatus['status'] }}">
                                        {{ $trialStatus['label'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($site->status === 'paused')
                                        <form action="{{ route('admin.sites.resume', $site) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn resume">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Resume
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.sites.pause', $site) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="action-btn pause">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Pause
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-xl">No websites found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sites->hasPages())
                <div class="mt-lg p-md border-t">
                    {{ $sites->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>