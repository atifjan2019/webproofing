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

        .form-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--color-border);
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .form-checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-checkbox {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: #000000;
        }

        .form-checkbox-label {
            font-size: 0.9375rem;
            color: var(--color-text-primary);
        }

        .form-checkbox-hint {
            font-size: 0.8125rem;
            color: var(--color-text-muted);
            margin-left: 2rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-submit {
            padding: 0.75rem 1.5rem;
            background: #000000;
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: #1a1a1a;
            transform: translateY(-1px);
        }

        .btn-cancel {
            padding: 0.75rem 1.5rem;
            background: transparent;
            color: var(--color-text-secondary);
            font-weight: 600;
            border: 1px solid var(--color-border);
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            border-color: var(--color-text-primary);
            color: var(--color-text-primary);
        }

        .error-text {
            color: #fa5252;
            font-size: 0.8125rem;
            margin-top: 0.25rem;
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
                <h1>Create New User</h1>
                <p>Add a new user account to the system</p>
            </div>
        </div>

        <!-- Form -->
        <div class="form-card animate-fadeInUp" style="animation-delay: 100ms;">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-checkbox-group">
                        <input type="checkbox" id="has_free_access" name="has_free_access" value="1"
                            class="form-checkbox" {{ old('has_free_access') ? 'checked' : '' }}>
                        <label for="has_free_access" class="form-checkbox-label">Grant Free Access</label>
                    </div>
                    <p class="form-checkbox-hint">User will have full access without requiring a subscription.</p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Create User</button>
                    <a href="{{ route('admin.users') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>