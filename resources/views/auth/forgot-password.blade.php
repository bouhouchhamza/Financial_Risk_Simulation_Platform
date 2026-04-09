<x-guest-layout>
    <h1 class="auth-title">Forgot Password</h1>
    <p class="auth-subtitle">Enter your email and we will send a reset link.</p>

    @if(session('status'))
        <div class="notice notice-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="form-grid">
        @csrf

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" class="input" value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="small text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Email Reset Link</button>
        </div>
    </form>
</x-guest-layout>
