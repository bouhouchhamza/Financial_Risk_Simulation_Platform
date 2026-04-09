<h2 class="card-title">Update Password</h2>
<p class="muted">Use a strong password to secure your account.</p>

<form method="POST" action="{{ route('password.update') }}" class="form-grid">
    @csrf
    @method('PUT')

    <div>
        <label for="current_password">Current Password</label>
        <input id="current_password" name="current_password" type="password" class="input" autocomplete="current-password">
        @if($errors->updatePassword->get('current_password'))
            <p class="small text-danger">{{ $errors->updatePassword->first('current_password') }}</p>
        @endif
    </div>

    <div>
        <label for="password">New Password</label>
        <input id="password" name="password" type="password" class="input" autocomplete="new-password">
        @if($errors->updatePassword->get('password'))
            <p class="small text-danger">{{ $errors->updatePassword->first('password') }}</p>
        @endif
    </div>

    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="input" autocomplete="new-password">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Password</button>
    </div>
</form>
