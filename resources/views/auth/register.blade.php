@extends('auth.auth')
@section('title', 'Register')

@section('content')

{{-- Referral notice --}}
@if(request()->has('ref') && $referrer)
<div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);border-radius:10px;padding:10px 14px;margin-bottom:16px;display:flex;align-items:center;gap:8px;font-size:0.82rem;color:var(--green);">
    <i class="bi bi-person-plus-fill"></i>
    You're being referred by <strong style="color:var(--text);margin-left:4px;">{{ $referrer->name }}</strong>
</div>
@endif

<div class="auth-card">
    <div class="auth-card-head">
        <div class="auth-card-title">Create account</div>
        <div class="auth-card-sub">Join TopTrade and start earning today</div>
    </div>
    <div class="auth-card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            @if(request()->has('ref'))
            <input type="hidden" name="ref" value="{{ request('ref') }}">
            @endif

            <div class="auth-field">
                <label class="auth-label" for="name">Full Name</label>
                <div class="auth-iw">
                    <i class="bi bi-person auth-ii"></i>
                    <input id="name" type="text" name="name"
                        class="auth-input @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Your full name"
                        required autocomplete="name" autofocus>
                </div>
                @error('name')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="email">Email Address</label>
                <div class="auth-iw">
                    <i class="bi bi-envelope auth-ii"></i>
                    <input id="email" type="email" name="email"
                        class="auth-input @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required autocomplete="email">
                </div>
                @error('email')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password">Password</label>
                <div class="auth-iw">
                    <i class="bi bi-lock auth-ii"></i>
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
                <label class="auth-label" for="password-confirm">Confirm Password</label>
                <div class="auth-iw">
                    <i class="bi bi-lock-fill auth-ii"></i>
                    <input id="password-confirm" type="password" name="password_confirmation"
                        class="auth-input"
                        placeholder="Repeat your password"
                        required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="auth-btn">
                <i class="bi bi-person-check-fill"></i> Create Account
            </button>

            <div style="display:flex;flex-direction:column;align-items:center;gap:10px;margin-top:16px;">
                <a href="{{ route('login') }}" class="auth-link">
                    Already have an account? <span style="color:var(--accent);font-weight:600;">Sign in</span>
                </a>
                <a href="{{ url('/') }}" class="auth-link">
                    <i class="bi bi-arrow-left" style="font-size:0.72rem;"></i> Back to Home
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
