<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WebProofing') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="auth-wrapper">
        <!-- Logo -->
        <a href="/" class="auth-logo">W</a>

        <!-- Card -->
        <div class="auth-card">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            © {{ date('Y') }} WebProofing. All rights reserved.
        </div>
    </div>
</body>

</html>