<nav class="navbar" x-data="{ open: false, profileOpen: false }">
    <div class="container">
        <div class="navbar-inner">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="navbar-brand">
                    <div class="navbar-logo">W</div>
                    <span class="navbar-title">Web<span class="text-accent">Proofing</span></span>
                </a>

                <!-- Navigation Links -->
                <div class="navbar-nav">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('sites.index') }}"
                            class="nav-link {{ request()->routeIs('sites.*') ? 'active' : '' }}">
                            Websites
                        </a>
                    @endauth
                    <a href="{{ route('pricing') }}"
                        class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}">
                        Pricing
                    </a>
                </div>
            </div>

            <!-- Right Side -->
            <div class="navbar-actions">
                @auth
                    <!-- Add Website Button -->
                    <a href="{{ route('sites.create') }}" class="btn btn-primary btn-sm">
                        <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Site</span>
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="dropdown" @click.away="profileOpen = false">
                        <button @click="profileOpen = !profileOpen" class="profile-trigger">
                            <div class="profile-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="profile-info">
                                <div class="profile-name">{{ Auth::user()->name }}</div>
                                <div class="profile-email">{{ Auth::user()->email }}</div>
                            </div>
                            <svg class="icon-md text-muted" fill="none" stroke="currentColor" viewBox="0 0 20 20"
                                :style="profileOpen ? 'transform: rotate(180deg)' : ''">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="dropdown-menu" x-show="profileOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1" x-cloak>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item danger">
                                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get Started</a>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <button @click="open = !open" class="mobile-toggle">
                <svg class="icon-lg" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" x-cloak />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-menu" :class="{ 'open': open }">
        <div class="flex flex-col gap-sm mb-lg">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('sites.index') }}" class="nav-link {{ request()->routeIs('sites.*') ? 'active' : '' }}">
                    Websites
                </a>
            @endauth
            <a href="{{ route('pricing') }}" class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}">
                Pricing
            </a>
            @auth
                <a href="{{ route('sites.create') }}" class="btn btn-primary btn-full mt-md">
                    Add New Website
                </a>
            @endauth
        </div>

        <div class="border-t pt-lg">
            @auth
                <div class="flex items-center gap-md mb-lg">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-black">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-muted">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="flex flex-col gap-sm">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-danger w-full text-left">Sign Out</button>
                    </form>
                </div>
            @else
                <div class="flex flex-col gap-sm">
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-full">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-full">Get Started</a>
                </div>
            @endauth
        </div>
    </div>
</nav>