@extends('auth.auth')

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-5 col-md-7 col-sm-10">
                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                        <h4 class="mb-0">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        <form id="login-form" method="POST" action="{{ route('backend.login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input id="email" type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input id="password" type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" placeholder="Enter your password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>

                            <!-- Button -->
                            <div class="d-grid">
                                <button type="button"
                                    onclick="submitWithLoading('login-form', {
                                        title: 'Logging in...',
                                        text: 'Verifying your credentials',
                                        delay: 500
                                    })"
                                    class="btn btn-primary btn-lg">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Login
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
