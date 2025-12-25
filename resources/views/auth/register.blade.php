<x-guest-layout>
    <div class="auth-header">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Get started with your free trial today</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" required autofocus
                autocomplete="name" placeholder="John Doe">
            @error('name')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required
                autocomplete="username" placeholder="you@example.com">
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-input" required autocomplete="new-password"
                placeholder="At least 8 characters">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required
                autocomplete="new-password" placeholder="Confirm your password">
            @error('password_confirmation')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary btn-full btn-lg">
            Create Account
        </button>

        <!-- Login Link -->
        <p class="auth-switch">
            Already have an account?
            <a href="{{ route('login') }}" class="auth-switch-link">Sign in</a>
        </p>
    </form>
</x-guest-layout>