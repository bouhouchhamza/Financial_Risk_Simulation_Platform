<x-guest-layout>
<div class="login-wrapper">

    <div class="login-container">

        <!-- BRAND -->
        <div class="login-brand">
            <div class="logo-box">SF</div>
            <div>
                <h1 class="brand-title">Sentinel Finance</h1>
                <span class="brand-sub">SECURE ACCESS</span>
            </div>
        </div>

        <!-- CARD -->
        <div class="login-card">
            <div>
                <h2 class="login-title">Access Terminal</h2>
                <p class="login-subtitle">Enter your credentials to manage your digital assets.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <div class="password-row">
                        <label>Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Forgot?</a>
                        @endif
                    </div>

                    <input type="password" name="password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>
        </div>

        <!-- FOOTER -->
        <div class="login-footer">
            (c) Sentinel Finance - Secure System
        </div>

    </div>

</div>
</x-guest-layout>
