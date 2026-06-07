@extends('auth.auth')
@section('title', 'Reset Password')

@section('content')

<div class="auth-card">
    <div class="auth-card-head">
        <div class="auth-card-title">Reset password</div>
        <div class="auth-card-sub">Enter your new password below</div>
    </div>
    <div class="auth-card-body">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="auth-field">
                <label class="auth-label" for="email">Email Address</label>
                <div class="auth-iw">
                    <i class="bi bi-envelope auth-ii"></i>
                    <input id="email" type="email" name="email"
                        class="auth-input @error('email') is-invalid @enderror"
                        value="{{ $email ?? old('email') }}"
                        placeholder="you@example.com"
                        required autocomplete="email" autofocus>
                </div>
                @error('email')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password">New Password</label>
                <div class="auth-iw">
                    <i class="bi bi-key auth-ii"></i>
                    <input id="password" type="password" name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="Min. 8 characters"
                        required autocomplete="new-password">
                </div>
                @error('password')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field" style="margin-bottom:20px;">
                <label class="auth-label" for="password-confirm">Confirm New Password</label>
                <div class="auth-iw">
                    <i class="bi bi-key-fill auth-ii"></i>
                    <input id="password-confirm" type="password" name="password_confirmation"
                        class="auth-input"
                        placeholder="Repeat your new password"
                        required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="auth-btn">
                <i class="bi bi-shield-check-fill"></i> Reset Password
            </button>

            <div style="display:flex;justify-content:center;margin-top:16px;">
                <a href="{{ url('/') }}" class="auth-link">
                    <i class="bi bi-arrow-left" style="font-size:0.72rem;"></i> Back to Home
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
