@extends('layouts.app')
@section('title', 'Investment Opportunities')
@section('page-title', 'Companies')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-building-fill" style="color:var(--accent);font-size:1.1rem;"></i> Investment Opportunities</h1>
        <p>Browse available companies and become a partner</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('companies.my-investments') }}" class="cy-hbtn outline">
            <i class="bi bi-briefcase-fill"></i> My Investments
        </a>
    </div>
</div>

<div class="co-grid">
    @forelse($companies as $company)
    <div class="co-card">

        {{-- Logo --}}
        <div class="co-img">
            @if($company->logo)
            <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}">
            @else
            <div class="co-img-placeholder">
                <i class="bi bi-building"></i>
            </div>
            @endif
        </div>

        {{-- Body --}}
        <div class="co-body">
            <div class="co-name">{{ $company->name }}</div>
            <div class="co-desc">{{ Str::limit($company->description, 100) }}</div>

            <div class="co-stats">
                <div class="co-stat">
                    <span class="co-stat-lbl">Share Price</span>
                    <span class="co-stat-val" style="color:var(--accent);">${{ number_format($company->share_price, 2) }}</span>
                </div>
                <div class="co-stat">
                    <span class="co-stat-lbl">Available</span>
                    <span class="co-stat-val" style="color:var(--green);">{{ number_format($company->available_shares, 2) }}%</span>
                </div>
                <div class="co-stat">
                    <span class="co-stat-lbl">Partners</span>
                    <span class="co-stat-val">{{ $company->total_partners }}</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="co-footer">
            <a href="{{ route('companies.show', $company->id) }}" class="cy-hbtn outline" style="flex:1;justify-content:center;font-size:0.78rem;">
                <i class="bi bi-eye"></i> View Details
            </a>
            @if($company->available_shares > 0)
            <a href="{{ route('companies.invest', $company->id) }}" class="cy-hbtn primary" style="flex:1;justify-content:center;font-size:0.78rem;">
                <i class="bi bi-cash-stack"></i> Invest Now
            </a>
            @else
            <button class="cy-hbtn" style="flex:1;justify-content:center;font-size:0.78rem;opacity:0.5;cursor:not-allowed;background:var(--card2);color:var(--muted);border:1px solid var(--border);" disabled>
                <i class="bi bi-x-circle"></i> No Shares
            </button>
            @endif
        </div>

    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1;padding:48px 20px;">
        <i class="bi bi-building"></i>
        <p>No companies available for investment at the moment</p>
    </div>
    @endforelse
</div>

@if($companies->hasPages())
<div style="margin-top:20px;">{{ $companies->links() }}</div>
@endif

@endsection

@push('scripts')
<style>
.co-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
}
.co-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: border-color 0.2s, transform 0.2s;
}
.co-card:hover { border-color: var(--border2); transform: translateY(-3px); }
.co-img { height: 160px; overflow: hidden; flex-shrink: 0; }
.co-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.co-img-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, var(--card2), rgba(0,245,212,0.05));
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; color: var(--muted);
}
.co-body { padding: 16px; flex: 1; }
.co-name { font-family: 'Syne', sans-serif; font-size: 0.95rem; font-weight: 800; margin-bottom: 6px; }
.co-desc { font-size: 0.78rem; color: var(--muted); line-height: 1.55; margin-bottom: 14px; }
.co-stats { display: flex; gap: 0; border: 1px solid var(--border); border-radius: 9px; overflow: hidden; }
.co-stat { flex: 1; padding: 8px; text-align: center; border-right: 1px solid var(--border); }
.co-stat:last-child { border-right: none; }
.co-stat-lbl { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); display: block; margin-bottom: 3px; }
.co-stat-val { font-family: 'Syne', sans-serif; font-size: 0.82rem; font-weight: 700; display: block; }
.co-footer { padding: 12px 16px; border-top: 1px solid var(--border); display: flex; gap: 8px; }
@media(max-width: 480px) { .co-grid { grid-template-columns: 1fr; } }
</style>
@endpush
