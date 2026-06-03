@extends('auth.auth')

@section('title')
Login
@endsection
@section('content')

<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ __('Login') }}</h2>
                    </div>

                    <div class="card-body">
                        <form id="login-form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus
                                    placeholder="Enter your email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button"
                                    onclick="submitWithLoading('login-form', {
                                        title: 'Logging in...',
                                        text: 'Verifying your credentials',
                                        delay: 500
                                    })"
                                    class="btn btn-primary btn-lg">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Login
                                </button>

                                @if (Route::has('password.request'))
                                    <div class="text-center mt-3">
                                        <a class="btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="text-center mt-3">
                                    <a class="btn-link" href="{{ url('/') }}">
                                        {{ __('Back to Home') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
