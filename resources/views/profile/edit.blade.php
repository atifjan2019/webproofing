<x-app-layout>
    <div class="container">
        <!-- Header -->
        <div class="flex flex-col gap-lg mb-xl" style="flex-direction: column;">
            <div class="flex items-center gap-md">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-icon">
                    <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-black">Profile Settings</h1>
                    <p class="text-muted text-sm mt-xs">Manage your account settings and preferences</p>
                </div>
            </div>
        </div>

        <div class="profile-grid">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar-large">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h2 class="profile-card-name">{{ Auth::user()->name }}</h2>
                    <p class="profile-card-email">{{ Auth::user()->email }}</p>
                    <p class="profile-card-joined">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="profile-content">
                <!-- Update Profile Information -->
                <section class="card card-body mb-lg">
                    <div class="card-section-header">
                        <div>
                            <h3 class="card-section-title">Profile Information</h3>
                            <p class="card-section-desc">Update your name and email address</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" name="name" type="text" class="form-input"
                                value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" name="email" type="email" class="form-input"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="alert alert-warning mt-md">
                                    <p class="text-sm">
                                        Your email address is unverified.
                                    <form id="send-verification" method="post" action="{{ route('verification.send') }}"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-accent font-medium underline">
                                            Resend verification email
                                        </button>
                                    </form>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-success text-sm" x-data="{ show: true }" x-show="show"
                                    x-init="setTimeout(() => show = false, 3000)">Saved successfully!</span>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- Update Password -->
                <section class="card card-body mb-lg">
                    <div class="card-section-header">
                        <div>
                            <h3 class="card-section-title">Change Password</h3>
                            <p class="card-section-desc">Ensure your account is using a secure password</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="profile-form">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input id="current_password" name="current_password" type="password" class="form-input"
                                autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input id="password" name="password" type="password" class="form-input"
                                autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-input" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                            @if (session('status') === 'password-updated')
                                <span class="text-success text-sm" x-data="{ show: true }" x-show="show"
                                    x-init="setTimeout(() => show = false, 3000)">Password updated!</span>
                            @endif
                        </div>
                    </form>
                </section>

                <!-- Delete Account -->
                <section class="card card-body" style="border-color: var(--color-danger-light);">
                    <div class="card-section-header">
                        <div>
                            <h3 class="card-section-title text-danger">Danger Zone</h3>
                            <p class="card-section-desc">Permanently delete your account and all data</p>
                        </div>
                    </div>

                    <div x-data="{ confirmDelete: false }">
                        <div x-show="!confirmDelete">
                            <p class="text-sm text-muted mb-md">
                                Once your account is deleted, all of its resources and data will be permanently deleted.
                            </p>
                            <button @click="confirmDelete = true" class="btn btn-danger">
                                Delete Account
                            </button>
                        </div>

                        <form x-show="confirmDelete" x-cloak method="post" action="{{ route('profile.destroy') }}"
                            class="profile-form">
                            @csrf
                            @method('delete')

                            <div class="alert alert-error mb-md">
                                <svg class="icon-md flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>This action cannot be undone. Please enter your password to confirm.</span>
                            </div>

                            <div class="form-group">
                                <label for="delete_password" class="form-label">Password</label>
                                <input id="delete_password" name="password" type="password" class="form-input"
                                    placeholder="Enter your password to confirm">
                                @error('password', 'userDeletion')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-actions">
                                <button type="button" @click="confirmDelete = false" class="btn btn-secondary">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    Yes, Delete My Account
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>