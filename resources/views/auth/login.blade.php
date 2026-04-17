<x-guest-layout>
<div class="login-wrapper">

    <div class="login-container">

        <!-- BRAND -->
        <div class="login-brand">
            <div class="logo-box">◆</div>
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
            © Sentinel Finance — Secure System
        </div>

    </div>

</div>

<style>
/* BACKGROUND */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #041424;
    position: relative;
}

/* glow effects */
.login-wrapper::before,
.login-wrapper::after {
    content: "";
    position: absolute;
    width: 500px;
    height: 400px;
    background: rgba(173,198,255,0.08);
    filter: blur(80px);
    border-radius: 50%;
}

.login-wrapper::before {
    top: -100px;
    right: -100px;
}

.login-wrapper::after {
    bottom: -100px;
    left: -100px;
}

/* CONTAINER */
.login-container {
    width: 420px;
    display: flex;
    flex-direction: column;
    gap: 40px;
    z-index: 2;
}

/* BRAND */
.login-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-box {
    width: 40px;
    height: 40px;
    background: #1B2B3C;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ADC6FF;
}

.brand-title {
    margin: 0;
    color: #ADC6FF;
    font-size: 22px;
    font-weight: 800;
}

.brand-sub {
    font-size: 10px;
    color: #C2C6D6;
    letter-spacing: 2px;
}

/* CARD */
.login-card {
    background: #0C1D2D;
    padding: 32px;
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.05);
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.login-title {
    margin: 0;
    color: #D3E4FA;
}

.login-subtitle {
    color: #C2C6D6;
    font-size: 14px;
}

/* FORM */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input-group label {
    font-size: 11px;
    text-transform: uppercase;
    color: #C2C6D6;
    margin-bottom: 6px;
    display: block;
}

.input-group input {
    width: 100%;
    padding: 14px;
    background: #102131;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    color: white;
}

.password-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.password-row a {
    font-size: 11px;
    color: #ADC6FF;
}

/* REMEMBER */
.remember {
    display: flex;
    gap: 10px;
    font-size: 14px;
    color: #C2C6D6;
}

/* BUTTON */
.login-btn {
    background: linear-gradient(135deg, #4D8EFF, #ADC6FF);
    border: none;
    padding: 14px;
    border-radius: 10px;
    font-weight: 700;
    color: #002E6A;
    cursor: pointer;
}

/* FOOTER */
.login-footer {
    text-align: center;
    font-size: 12px;
    color: rgba(140,144,159,0.6);
}

/* ERROR */
.error {
    color: #ff6b6b;
    font-size: 12px;
}
</style>

</x-guest-layout>