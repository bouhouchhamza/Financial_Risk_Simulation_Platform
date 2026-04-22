<x-guest-layout>
    <section class="auth-flow-page">
        <div class="auth-flow-header">
            <p class="auth-kicker">Account Recovery</p>
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Choose a new password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="form-grid">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="input" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="input" required autocomplete="new-password">
                @error('password')
                    <p class="small text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="input" required autocomplete="new-password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </section>
</x-guest-layout>
