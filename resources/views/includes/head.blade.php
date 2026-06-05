<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#050507">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    {{-- Wallet CSS (wallet, deposit, withdraw, transactions pages) --}}
    @if(request()->routeIs('wallet*') || request()->routeIs('withdraw*'))
    <link rel="stylesheet" href="{{ asset('assets/css/wallet.css') }}">
    @endif

    @if (Auth::user()->roles[0]->name == 'user')
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    @elseif (Auth::user()->roles[0]->name == 'admin')
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/style2.css') }}"> --}}
    @endif
    @yield('css')
</head>

<body>
