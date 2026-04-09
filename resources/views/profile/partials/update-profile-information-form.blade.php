<h2 class="card-title">Profile Information</h2>
<p class="muted">Update your account name and email address.</p>

<form method="POST" action="{{ route('profile.update') }}" class="form-grid">
    @csrf
    @method('PATCH')

    <div>
        <label for="name">Name</label>
        <input id="name" name="name" type="text" class="input" value="{{ old('name', $user->name) }}" required>
        @error('name')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" class="input" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <p class="small text-danger">{{ $message }}</p>
        @enderror
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="notice notice-error">
            <p class="small">Your email is not verified.</p>
            <button form="send-verification" class="btn" type="submit">Resend Verification Email</button>
        </div>
    @endif

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Profile</button>
    </div>
</form>

<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>
