<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PFF') }}</title>
    @vite('resources/css/app.css')
</head>
@php
    $isLoginPage = request()->routeIs('login');
    $isRegisterPage = request()->routeIs('register');
@endphp
<body class="{{ $isLoginPage ? 'login-page' : ($isRegisterPage ? 'register-page' : 'guest-auth-page') }}">
    @if($isLoginPage || $isRegisterPage)
        {{ $slot }}
    @else
        <div class="auth-shell">
            <div class="card auth-card">
                {{ $slot }}
            </div>
        </div>
    @endif
</body>
</html>
