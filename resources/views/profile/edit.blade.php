@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Profile</h1>
</div>

@if(session('status') === 'profile-updated')
    <div class="notice notice-success">Profile updated successfully.</div>
@endif

@if(session('status') === 'password-updated')
    <div class="notice notice-success">Password updated successfully.</div>
@endif

<div class="grid-2">
    <div class="card">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="card">
        @include('profile.partials.update-password-form')
    </div>
</div>

<div class="card">
    @include('profile.partials.delete-user-form')
</div>
@endsection
