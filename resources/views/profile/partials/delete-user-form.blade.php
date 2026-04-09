<h2 class="card-title">Delete Account</h2>
<p class="muted">This action is irreversible. Enter your current password to continue.</p>

<form method="POST" action="{{ route('profile.destroy') }}" class="form-grid">
    @csrf
    @method('DELETE')

    <div>
        <label for="delete_password">Current Password</label>
        <input id="delete_password" name="password" type="password" class="input" required autocomplete="current-password">
        @if($errors->userDeletion->get('password'))
            <p class="small text-danger">{{ $errors->userDeletion->first('password') }}</p>
        @endif
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?');">
            Delete Account
        </button>
    </div>
</form>
