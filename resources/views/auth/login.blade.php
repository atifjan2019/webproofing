<x-guest-layout>
    <div class="auth-header">
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="alert alert-success mb-lg" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required autofocus
                autocomplete="username" placeholder="you@example.com">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <div class="flex items-center justify-between">
                <label for="password" class="form-label">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="form-link">Forgot password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" class="form-input" required
                autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group">
            <label class="form-checkbox">
                <input type="checkbox" name="remember" class="checkbox-input">
                <span class="checkbox-label">Remember me for 30 days</span>
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary btn-full btn-lg">
            Sign in
        </button>

        <!-- Register Link -->
        <p class="auth-switch">
            Don't have an account?
            <a href="{{ route('register') }}" class="auth-switch-link">Create one</a>
        </p>
    </form>
</x-guest-layout>