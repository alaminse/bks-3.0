@extends('auth.auth')

@section('title')
Verify Email
@endsection

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ __('Verify Your Email Address') }}</h2>
                    </div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p style="color: #cbd5e1; margin-bottom: 20px;">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                        </p>

                        <p style="color: #cbd5e1; margin-bottom: 30px;">
                            {{ __('If you did not receive the email') }},
                        </p>

                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Resend Verification Email') }}
                                </button>

                                <div class="text-center mt-3">
                                    <a class="btn-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </div>
                            </div>
                        </form>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
