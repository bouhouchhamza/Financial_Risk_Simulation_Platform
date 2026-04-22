<x-guest-layout>
    <section class="auth-flow-page">
        <div class="auth-flow-header">
            <p class="auth-kicker">Security Check</p>
            <h1 class="auth-title">Confirm Password</h1>
            <p class="auth-subtitle">Please confirm your password to continue.</p>
        </div>

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
    </section>
</x-guest-layout>
