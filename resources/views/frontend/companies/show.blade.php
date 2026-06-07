@extends('layouts.app')
@section('title', $company->name)
@section('page-title', 'Company Details')

@section('content')

<div class="page-header-bar">
    <div>
        <div style="font-size:0.72rem;color:var(--muted);margin-bottom:4px;">
            <a href="{{ route('companies.index') }}" style="color:var(--muted);text-decoration:none;">Companies</a>
            <i class="bi bi-chevron-right" style="font-size:0.6rem;margin:0 4px;"></i>
            {{ $company->name }}
        </div>
        <h1><i class="bi bi-building-fill" style="color:var(--accent);font-size:1.1rem;"></i> {{ $company->name }}</h1>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('companies.index') }}" class="cy-hbtn outline">
            <i class="bi bi-arrow-left"></i> All Companies
        </a>
    </div>
</div>

<div class="cs-layout">

    {{-- LEFT --}}
    <div>

        {{-- Company Image + Info --}}
        <div class="s-card cs-mb">
            @if($company->logo)
            <div style="height:220px;overflow:hidden;border-radius:14px 14px 0 0;">
                <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" style="width:100%;height:100%;object-fit:cover;">
            </div>
            @endif
            <div style="padding:20px;">
                <div style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;margin-bottom:8px;">{{ $company->name }}</div>
                <div style="font-size:0.875rem;color:var(--muted);line-height:1.7;white-space:pre-line;">{{ $company->description }}</div>
            </div>

            {{-- Stats grid --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border-top:1px solid var(--border);">
                @foreach([
                    ['lbl'=>'Total Value',      'val'=>'$'.number_format($company->total_value,2),             'c'=>'var(--accent)'],
                    ['lbl'=>'Share Price',       'val'=>'$'.number_format($company->share_price,2),             'c'=>'var(--green)'],
                    ['lbl'=>'Available Shares',  'val'=>number_format($company->available_shares,2).'%',        'c'=>'var(--gold)'],
                    ['lbl'=>'Total Partners',    'val'=>$company->total_partners,                               'c'=>'var(--blue)'],
                ] as $s)
                <div style="background:var(--card);padding:14px 10px;text-align:center;">
                    <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">{{ $s['lbl'] }}</div>
                    <div style="font-family:'Syne',sans-serif;font-size:0.9rem;font-weight:800;color:{{ $s['c'] }};">{{ $s['val'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- My Partnership --}}
        @if($userShare)
        <div class="s-card cs-mb" style="border-color:rgba(0,245,212,0.3) !important;">
            <div class="s-card-head">
                <span class="s-card-title" style="color:var(--accent);"><i class="bi bi-person-check-fill"></i> You are a Partner</span>
                <span class="s-pill approved">Active</span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);">
                @foreach([
                    ['lbl'=>'Investment',  'val'=>'$'.number_format($userShare->invested_amount,2), 'c'=>'var(--accent)'],
                    ['lbl'=>'Your Shares', 'val'=>number_format($userShare->share_quantity,2),       'c'=>'var(--green)'],
                    ['lbl'=>'Ownership',   'val'=>number_format($userShare->share_percentage,2).'%', 'c'=>'var(--blue)'],
                ] as $s)
                <div style="background:var(--card);padding:14px;text-align:center;">
                    <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">{{ $s['lbl'] }}</div>
                    <div style="font-family:'Syne',sans-serif;font-size:0.95rem;font-weight:800;color:{{ $s['c'] }};">{{ $s['val'] }}</div>
                </div>
                @endforeach
            </div>
            <div style="padding:12px 18px;font-size:0.75rem;color:var(--muted);">
                <i class="bi bi-calendar3" style="color:var(--accent);margin-right:4px;"></i>
                Partner since {{ $userShare->purchase_date->format('d M Y') }}
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT SIDEBAR --}}
    <div>

        {{-- Wallet --}}
        <div class="s-card cs-mb">
            <div style="padding:16px 18px;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">Your Balance</div>
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);">${{ Auth::user()->wallet_balance }}</div>
                </div>
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,245,212,0.1);border:1px solid rgba(0,245,212,0.2);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1.1rem;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>

        {{-- Action --}}
        <div class="s-card cs-mb">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-cash-stack"></i> Investment Action</span>
            </div>
            <div style="padding:16px 18px;display:flex;flex-direction:column;gap:8px;">
                @if($company->available_shares > 0)
                <a href="{{ route('companies.invest', $company->id) }}" class="cy-hbtn primary" style="justify-content:center;padding:12px;font-size:0.88rem;">
                    <i class="bi bi-cash-stack"></i> Invest Now
                </a>
                @else
                <button class="cy-hbtn" style="justify-content:center;padding:12px;opacity:0.5;cursor:not-allowed;background:var(--card2);color:var(--muted);border:1px solid var(--border);" disabled>
                    <i class="bi bi-x-circle"></i> No Shares Available
                </button>
                @endif
                <a href="{{ route('companies.index') }}" class="cy-hbtn outline" style="justify-content:center;">
                    <i class="bi bi-arrow-left"></i> Back to Companies
                </a>
            </div>
            <div style="border-top:1px solid var(--border);">
                <div style="display:flex;justify-content:space-between;padding:10px 18px;font-size:0.82rem;border-bottom:1px solid var(--border);">
                    <span style="color:var(--muted);">Share Price</span>
                    <span style="font-weight:700;color:var(--accent);">${{ number_format($company->share_price, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 18px;font-size:0.82rem;">
                    <span style="color:var(--muted);">Available</span>
                    <span style="font-weight:700;color:var(--green);">{{ number_format($company->available_shares, 2) }}%</span>
                </div>
            </div>
        </div>

        @if($userShare)
        {{-- Quick Stats --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-graph-up-arrow"></i> Quick Stats</span>
            </div>
            <div style="padding:4px 0;">
                @foreach([
                    ['lbl'=>'Purchase Date', 'val'=>$userShare->purchase_date->format('d M Y')],
                    ['lbl'=>'Status',        'val'=>ucfirst($userShare->status)],
                ] as $row)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.82rem;color:var(--muted);">{{ $row['lbl'] }}</span>
                    <span style="font-size:0.82rem;font-weight:600;">{{ $row['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<style>
.cs-layout { display: grid; grid-template-columns: 1fr 280px; gap: 16px; align-items: start; }
.cs-mb { margin-bottom: 12px; }
@media(max-width: 900px) { .cs-layout { grid-template-columns: 1fr; } }
@media(max-width: 480px) {
    [style*="grid-template-columns: repeat(4"] { grid-template-columns: 1fr 1fr !important; }
    [style*="grid-template-columns: repeat(3"] { grid-template-columns: 1fr 1fr 1fr !important; }
}
</style>
@endpush
