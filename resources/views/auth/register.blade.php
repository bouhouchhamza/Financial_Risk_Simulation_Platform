<x-guest-layout>
    <h1 class="auth-title">Create Account</h1>
    <p class="auth-subtitle">Start managing your startup finances securely.</p>

    <form method="POST" action="{{ route('register') }}" class="form-grid">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" class="input" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <p class="small text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" class="input" value="{{ old('email') }}" required autocomplete="username">
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
            <button type="submit" class="btn btn-primary">Register</button>
            <a class="link small" href="{{ route('login') }}">Already registered?</a>
        </div>
    </form>
</x-guest-layout>
