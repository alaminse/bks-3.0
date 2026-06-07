<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#050507">
    <title>@yield('title') — {{ config('app.name') }}</title>

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Your existing CSS (brings in all CSS variables + theme) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    {{-- Auth-specific styles (small, no duplication) --}}
    <style>
    html { height: 100%; }
    body {
        min-height: 100vh;
        background: var(--black);
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 16px;
    }
    body::before {
        content: '';
        position: fixed; inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 15% 20%, rgba(99,102,241,0.12), transparent),
            radial-gradient(ellipse 50% 40% at 85% 80%, rgba(0,245,212,0.08), transparent);
        pointer-events: none; z-index: 0;
    }
    .auth-wrap {
        position: relative; z-index: 1;
        width: 100%; max-width: 420px;
        margin: auto;
    }
    .auth-logo {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        margin-bottom: 28px;
    }
    .auth-logo-mark {
        width: 38px; height: 38px; border-radius: 10px;
        background: var(--accent); color: #000;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800;
    }
    .auth-logo-text {
        font-family: 'Syne', sans-serif;
        font-size: 1.2rem; font-weight: 800; letter-spacing: 0.5px;
    }
    .auth-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        position: relative;
    }
    .auth-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }
    .auth-card-head { padding: 24px 28px 0; }
    .auth-card-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.3rem; font-weight: 800; margin-bottom: 4px;
    }
    .auth-card-sub { font-size: 0.82rem; color: var(--muted); }
    .auth-card-body { padding: 24px 28px 28px; }

    /* Fields — reuse your pf-input style */
    .auth-field { margin-bottom: 16px; }
    .auth-label {
        display: block; font-size: 0.75rem; font-weight: 600;
        color: var(--muted); margin-bottom: 6px;
    }
    .auth-iw { position: relative; }
    .auth-ii {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%);
        color: var(--muted); font-size: 0.9rem; pointer-events: none;
    }
    .auth-input {
        width: 100%;
        background: var(--card2) !important;
        border: 1px solid var(--border2) !important;
        border-radius: 9px !important;
        color: var(--text) !important;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        padding: 11px 14px 11px 38px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        -webkit-appearance: none;
    }
    .auth-input.no-icon { padding-left: 14px; }
    .auth-input::placeholder { color: var(--muted) !important; }
    .auth-input:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(0,245,212,0.1) !important;
    }
    .auth-input.is-invalid { border-color: var(--red) !important; }
    .auth-error { font-size: 0.72rem; color: var(--red); margin-top: 4px; display:flex;gap:4px;align-items:center; }

    .auth-check-row {
        display: flex; align-items: center; gap: 8px;
    }
    .auth-check-row input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: var(--accent); cursor: pointer; flex-shrink: 0;
    }
    .auth-check-row label { font-size: 0.82rem; color: var(--muted); cursor: pointer; }

    /* Reuse your cy-hbtn primary style */
    .auth-btn {
        width: 100%; padding: 12px;
        background: var(--accent); color: #000;
        border: none; border-radius: 9px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem; font-weight: 700;
        cursor: pointer; transition: opacity 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 7px;
    }
    .auth-btn:hover { opacity: 0.9; }

    .auth-link {
        font-size: 0.82rem; color: var(--muted);
        text-decoration: none; transition: color 0.15s;
    }
    .auth-link:hover { color: var(--accent); }

    .auth-brand-note {
        text-align: center; margin-top: 20px;
        font-size: 0.72rem; color: var(--muted);
    }

    @media(max-width: 480px) {
        .auth-card-head { padding: 20px 20px 0; }
        .auth-card-body { padding: 20px; }
    }
    </style>

    @yield('css')
</head>
<body>

<div class="auth-wrap">

    <div class="auth-logo">
        <div class="auth-logo-mark">T</div>
        <span class="auth-logo-text">TopTrade</span>
    </div>

    @yield('content')

    <div class="auth-brand-note">
        &copy; {{ date('Y') }} TopTrade. All rights reserved.
    </div>

</div>

{{-- Theme switcher (optional — gives auth page same theme as dashboard) --}}
<script src="{{ asset('assets/js/theme.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/sweetalert-helper.js') }}"></script>

@stack('scripts')

<script>
@if(session('success'))
    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'{{ addslashes(session("success")) }}', showConfirmButton:false, timer:3000 });
@endif
@if(session('error'))
    Swal.fire({ icon:'error', title:'Error', text:'{{ addslashes(session("error")) }}' });
@endif
</script>

</body>
</html>
