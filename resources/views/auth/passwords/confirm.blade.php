@extends('auth.auth')
@section('title', 'Confirm Password')

@section('content')

<div class="auth-card">
    <div class="auth-card-head">
        <div class="auth-card-title">Confirm password</div>
        <div class="auth-card-sub">Please confirm your password before continuing</div>
    </div>
    <div class="auth-card-body">
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="auth-field" style="margin-bottom:20px;">
                <label class="auth-label" for="password">Password</label>
                <div class="auth-iw">
                    <i class="bi bi-lock auth-ii"></i>
                    <input id="password" type="password" name="password"
                        class="auth-input @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required autocomplete="current-password">
                </div>
                @error('password')
                <div class="auth-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="auth-btn">
                <i class="bi bi-check-circle-fill"></i> Confirm Password
            </button>

            @if(Route::has('password.request'))
            <div style="display:flex;justify-content:center;margin-top:16px;">
                <a href="{{ route('password.request') }}" class="auth-link">Forgot your password?</a>
            </div>
            @endif

        </form>
    </div>
</div>

@endsection
