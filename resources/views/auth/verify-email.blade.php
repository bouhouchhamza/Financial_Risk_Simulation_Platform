<x-guest-layout>
    <section class="auth-flow-page auth-verify-page">
        <div class="auth-flow-header">
            <p class="auth-kicker">Email Verification</p>
            <h1 class="auth-title">Verify Email</h1>
            <p class="auth-subtitle">
                Check your inbox and click the verification link. If you did not receive it, resend below.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="notice notice-success">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="form-actions auth-flow-actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn">Log Out</button>
            </form>
        </div>
    </section>
</x-guest-layout>
