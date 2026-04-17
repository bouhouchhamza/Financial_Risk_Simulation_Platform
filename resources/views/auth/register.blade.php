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

    <style>
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 64px 16px;
            background: #041424;
            position: relative;
            overflow: hidden;
        }

        .register-wrapper::before,
        .register-wrapper::after {
            content: "";
            position: absolute;
            width: 384px;
            height: 384px;
            border-radius: 9999px;
            filter: blur(60px);
            z-index: 0;
        }

        .register-wrapper::before {
            right: -80px;
            top: 20%;
            background: rgba(173, 198, 255, 0.05);
        }

        .register-wrapper::after {
            left: -80px;
            top: 40%;
            background: rgba(74, 225, 118, 0.05);
        }

        .register-shell {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .register-card {
            position: relative;
            background: #0C1D2D;
            border: 1px solid rgba(66, 71, 84, 0.10);
            border-radius: 12px;
            padding: 40px;
            box-sizing: border-box;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .top-accent-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 12px 12px 0 0;
            background: linear-gradient(90deg, rgba(173, 198, 255, 0.5) 0%, rgba(173, 198, 255, 0) 100%);
        }

        .register-header {
            text-align: center;
        }

        .register-title {
            margin: 0 0 8px;
            font-family: 'Manrope', sans-serif;
            font-size: 30px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.75px;
            color: #D3E4FA;
        }

        .register-subtitle {
            margin: 0;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 500;
            line-height: 1.5;
            color: #C2C6D6;
            opacity: 0.85;
        }

        .register-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field-group label {
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 600;
            line-height: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #C2C6D6;
            padding-left: 4px;
        }

        .field-group input {
            width: 100%;
            height: 55px;
            box-sizing: border-box;
            border: 1px solid rgba(66, 71, 84, 0.18);
            border-radius: 8px;
            background: #102131;
            color: #E8EEF8;
            padding: 0 16px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field-group input::placeholder {
            color: #8C909F;
        }

        .field-group input:focus {
            border-color: rgba(173, 198, 255, 0.45);
            box-shadow: 0 0 0 3px rgba(173, 198, 255, 0.08);
        }

        .password-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: #C2C6D6;
            font-size: 14px;
            line-height: 1.35;
            cursor: pointer;
        }

        .terms-row input {
            width: 16px;
            height: 16px;
            margin-top: 2px;
            accent-color: #ADC6FF;
        }

        .register-btn {
            width: 100%;
            height: 60px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(90deg, #4D8EFF 0%, #ADC6FF 100%);
            color: #002E6A;
            font-family: 'Manrope', sans-serif;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 24px rgba(77, 142, 255, 0.20);
        }

        .register-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(77, 142, 255, 0.26);
        }

        .register-alt {
            border-top: 1px solid rgba(66, 71, 84, 0.10);
            padding-top: 24px;
            text-align: center;
            color: #C2C6D6;
            font-size: 14px;
        }

        .register-alt a {
            color: #ADC6FF;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .register-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 16px;
            color: rgba(140, 144, 159, 0.45);
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .register-meta div {
            display: flex;
            gap: 24px;
        }

        .register-meta a {
            color: rgba(140, 144, 159, 0.45);
            text-decoration: none;
        }

        .field-error {
            margin: 0;
            font-size: 12px;
            color: #ff7d7d;
            padding-left: 4px;
        }

        @media (max-width: 640px) {
            .register-card {
                padding: 24px;
                gap: 28px;
            }

            .password-grid {
                grid-template-columns: 1fr;
            }

            .register-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</x-guest-layout>