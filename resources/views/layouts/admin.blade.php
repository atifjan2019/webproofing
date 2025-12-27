<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - WebProofing</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="page-wrapper">
        <!-- Simple Admin Header -->
        <nav class="navbar" style="background: #000000;">
            <div class="container">
                <div class="navbar-inner" style="justify-content: space-between;">
                    <a href="{{ route('admin.dashboard') }}" class="navbar-brand" style="color: #ffffff;">
                        <div class="navbar-logo" style="background: #ffffff; color: #000000;">A</div>
                        <span class="navbar-title" style="color: #ffffff;">Admin<span
                                style="color: #ff6b7a;">Panel</span></span>
                    </a>

                    <div class="flex items-center gap-md">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm"
                            style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: #ffffff;">
                            <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to App
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm"
                                style="background: transparent; border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.7);">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="main-content">
            {{ $slot }}
        </main>

        <!-- Simple Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-inner">
                    <div class="footer-brand">
                        <span class="footer-logo">A.</span>
                        <span class="footer-copyright">© {{ date('Y') }} WebProofing Admin Panel</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>