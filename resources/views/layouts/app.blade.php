<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WebProofing - Website Monitoring</title>
    <meta name="description"
        content="Professional website monitoring and screenshot tracking. Keep your digital properties safe and monitored 24/7.">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="WebProofing">
    <meta property="og:title" content="WebProofing - Website Monitoring">
    <meta property="og:description"
        content="Professional website monitoring and screenshot tracking. Keep your digital properties safe and monitored 24/7.">
    <meta property="og:image" content="{{ url('/favicon.svg') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="WebProofing - Website Monitoring">
    <meta name="twitter:description" content="Professional website monitoring and screenshot tracking.">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Smartlook -->
    <script type='text/javascript'>
        window.smartlook || (function (d) {
            var o = smartlook = function () { o.api.push(arguments) }, h = d.getElementsByTagName('head')[0];
            var c = d.createElement('script'); o.api = new Array(); c.async = true; c.type = 'text/javascript';
            c.charset = 'utf-8'; c.src = 'https://web-sdk.smartlook.com/recorder.js'; h.appendChild(c);
        })(document);
        smartlook('init', 'f4e8e05c4d47d6a6a69c0442d470546c3579c393', { region: 'eu' });
    </script>
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