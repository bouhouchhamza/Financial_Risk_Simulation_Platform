<x-guest-layout>
    <div class="register-wrapper">
        <div class="register-shell">

            <div class="register-card">
                <div class="top-accent-line"></div>

                <div class="register-header">
                    <h1 class="register-title">Create Account</h1>
                    <p class="register-subtitle">Start managing your startup finances securely.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="register-form">
                    @csrf

                    <div class="field-group">
                        <label for="name">Full Identity</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Enter your full name">
                        @error('name')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field-group">
                        <label for="email">Secure Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="name@company.com">
                        @error('email')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="password-grid">
                        <div class="field-group">
                            <label for="password">Access Key</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••">
                            @error('password')
                            <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="password_confirmation">Verify Key</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="register-btn">
                        Create Account
                    </button>
                </form>

                <div class="register-alt">
                    <span>Already registered?</span>
                    <a href="{{ route('login') }}">Authenticate here</a>
                </div>
            </div>

            <div class="register-meta">
                <span>SECURE ONBOARDING PORTAL</span>
                <div>
                    <a href="#">Privacy</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </div>
    </div>

    
</x-guest-layout>