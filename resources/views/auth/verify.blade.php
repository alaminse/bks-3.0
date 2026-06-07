@extends('auth.auth')
@section('title', 'Verify Email')

@section('content')

<div class="auth-card">
    <div class="auth-card-head">
        <div class="auth-card-title">Check your email 📬</div>
        <div class="auth-card-sub">We sent a verification link to your inbox</div>
    </div>
    <div class="auth-card-body">

        @if(session('resent'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);border-radius:10px;padding:12px 14px;margin-bottom:16px;display:flex;gap:8px;align-items:flex-start;font-size:0.82rem;color:var(--green);">
            <i class="bi bi-check-circle-fill" style="flex-shrink:0;margin-top:2px;"></i>
            A fresh verification link has been sent to your email address.
        </div>
        @endif

        {{-- Info box --}}
        <div style="background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:20px;">
            <div style="display:flex;gap:10px;align-items:flex-start;">
                <div style="width:36px;height:36px;border-radius:9px;background:rgba(0,245,212,0.1);border:1px solid rgba(0,245,212,0.2);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:0.88rem;margin-bottom:4px;">Verification email sent</div>
                    <div style="font-size:0.78rem;color:var(--muted);line-height:1.6;">
                        Please check your email for a verification link. If you don't see it, check your spam folder.
                    </div>
                </div>
            </div>
        </div>

        {{-- Resend --}}
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="auth-btn" style="margin-bottom:12px;">
                <i class="bi bi-send-fill"></i> Resend Verification Email
            </button>
        </form>

        {{-- Logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button"
                onclick="document.getElementById('logout-form').submit();"
                class="auth-btn"
                style="background:transparent;color:var(--muted);border:1px solid var(--border);font-weight:500;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>

    </div>
</div>

@endsection
