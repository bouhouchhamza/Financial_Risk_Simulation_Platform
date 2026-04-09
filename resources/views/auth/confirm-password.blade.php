<x-guest-layout>
    <h1 class="auth-title">Confirm Password</h1>
    <p class="auth-subtitle">Please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="form-grid">
        @csrf

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" class="input" required autocomplete="current-password">
            @error('password')
                <p class="small text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
    </form>
</x-guest-layout>
