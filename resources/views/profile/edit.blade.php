@extends('layouts.app')

@section('content')
<section class="unified-user-page profile-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h1 class="page-title">Profile</h1>
            <p class="page-subtitle">Manage your account identity, credentials, and security settings.</p>
        </div>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="notice notice-success">Profile updated successfully.</div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="notice notice-success">Password updated successfully.</div>
    @endif

    <div class="grid-2">
        <div class="card dashboard-panel">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card dashboard-panel">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card dashboard-panel">
        @include('profile.partials.delete-user-form')
    </div>
</section>
@endsection
