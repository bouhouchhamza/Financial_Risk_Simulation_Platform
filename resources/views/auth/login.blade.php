<x-guest-layout>
    <h1 class="auth-title">Sign In</h1>
    <p class="auth-subtitle">Access your fintech dashboard.</p>

    @if(session('status'))
        <div class="notice notice-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="form-grid">
        @csrf

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" class="input" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="small text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" class="input" required autocomplete="current-password">
            @error('password')
                <p class="small text-danger">{{ $message }}</p>
            @enderror
        </div>

        <label>
            <input id="remember_me" type="checkbox" name="remember">
            Remember me
        </label>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Log In</button>
            @if (Route::has('password.request'))
                <a class="link small" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>
    </form>
</x-guest-layout>
