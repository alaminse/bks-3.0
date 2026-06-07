@extends('auth.auth')
@section('title', 'Login')

@section('content')

<div class="auth-card">
    <div class="auth-card-head">
        <div class="auth-card-title">Welcome back 👋</div>
        <div class="auth-card-sub">Sign in to your TopTrade account</div>
    </div>
    <div class="auth-card-body">
        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="auth-field">
                <label class="auth-label" for="email">Email Address</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-envelope auth-input-icon"></i>
                    <input id="email" type="email" name="email"
                        class="auth-input @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required autocomplete="email" autofocus>
                </div>
                @error('email')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="auth-field">
                <label class="auth-label" for="password">Password</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-lock auth-input-icon"></i>
                    <input id="password" type="password" name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required autocomplete="current-password">
                </div>
                @error('password')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Remember + Forgot --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <div class="auth-check-row" style="margin-bottom:0;">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me</label>
                </div>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link" style="font-size:0.78rem;">
                    Forgot password?
                </a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="button" class="auth-btn"
                onclick="submitWithLoading('login-form', {
                    title: 'Logging in...',
                    text: 'Verifying your credentials',
                    delay: 500
                })">
                <i class="bi bi-arrow-right-circle-fill"></i> Sign In
            </button>

        </form>

        <div class="auth-links">
            <a href="{{ url('/') }}" class="auth-link">
                <i class="bi bi-arrow-left" style="font-size:0.72rem;"></i> Back to Home
            </a>
        </div>

    </div>
</div>

{{-- Register link --}}
@if(Route::has('register'))
<div style="text-align:center;margin-top:16px;font-size:0.82rem;color:var(--muted);">
    Don't have an account?
    <a href="{{ route('register') }}" style="color:var(--accent);font-weight:600;text-decoration:none;margin-left:4px;">Create one →</a>
</div>
@endif

@endsection
