<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WebProofing') }} - Website Monitoring</title>
    <meta name="description"
        content="Professional website monitoring and screenshot tracking. Keep your digital properties safe and monitored 24/7.">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="page-wrapper">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="main-content">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-inner">
                    <div class="footer-brand">
                        <span class="footer-logo">W.</span>
                        <span class="footer-copyright">© {{ date('Y') }} WebProofing. All rights reserved.</span>
                    </div>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Privacy</a>
                        <a href="#" class="footer-link">Terms</a>
                        <a href="#" class="footer-link">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>