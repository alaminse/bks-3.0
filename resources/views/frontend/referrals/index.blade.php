@extends('layouts.app')
@section('title', 'Referrals')
@section('page-title', 'Referrals')

@section('css')
<style>
    .rf-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
    }

    @media(max-width: 900px) {
        .rf-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
@section('content')

{{-- PAGE HEADER --}}
<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-people-fill" style="color:var(--accent);font-size:1.1rem;"></i> Referral Dashboard</h1>
        <p>Invite friends and earn commission on every purchase they make</p>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-people-fill"></i></div>
        <div class="stat-card-lbl">Total Referrals</div>
        <div class="stat-card-val" style="color:var(--accent);">{{ $stats['total_referrals'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">All Time</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);"><i class="bi bi-person-check-fill"></i></div>
        <div class="stat-card-lbl">Active</div>
        <div class="stat-card-val" style="color:var(--green);">{{ $stats['active_referrals'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-up">Active</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-card-lbl">Pending</div>
        <div class="stat-card-val" style="color:var(--gold);">{{ $stats['pending_referrals'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">Awaiting</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--blue);"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-card-lbl">Total Earned</div>
        <div class="stat-card-val" style="color:var(--blue);">${{ number_format($stats['total_earnings'], 2) }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-up">Commission</span></div>
    </div>
</div>

{{-- HERO GRID --}}
<div class="rf-grid">

    {{-- LEFT: REFERRAL LINK --}}
    <div class="s-card">
        <div class="s-card-head">
            <span class="s-card-title"><i class="bi bi-link-45deg"></i> Your Referral Link</span>
            <span class="rf-comm-badge">
                <i class="bi bi-percent"></i> 10% Commission
            </span>
        </div>
        <div class="rf-link-body">

            {{-- Link input --}}
            <div style="margin-bottom:14px;">
                <div style="font-size:0.72rem;color:var(--muted);margin-bottom:6px;">Share this link with friends</div>
                <div class="rf-input-row">
                    <input type="text" id="referralLink"
                        value="{{ $user->getReferralLink() }}"
                        readonly class="rf-input">
                    <button id="copyBtn" onclick="copyLink()" class="rf-copy-btn">
                        <i class="bi bi-clipboard" id="copyIcon"></i>
                        <span id="copyText">Copy</span>
                    </button>
                </div>
            </div>

            {{-- Referral code --}}
            <div class="rf-code-box">
                <div>
                    <div class="rf-code-lbl">Your Code</div>
                    <div class="rf-code-val">{{ $user->referral_code }}</div>
                </div>
                <button onclick="copyCode()" class="rf-code-copy">
                    <i class="bi bi-clipboard"></i> Copy Code
                </button>
            </div>

            {{-- Commission note --}}
            <div class="rf-comm-note">
                <div class="rf-comm-pct">10%</div>
                <div class="rf-comm-desc">
                    Earn <strong style="color:var(--accent);">10% commission</strong> on every package purchase made by users you refer. Credits instantly to your wallet.
                </div>
            </div>

            {{-- Share buttons --}}
            <div>
                <div style="font-size:0.65rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">Share via</div>
                <div class="rf-share-row">
                    <a href="https://wa.me/?text={{ urlencode('Join me on TopTrade and start earning daily! '.$user->getReferralLink()) }}"
                        target="_blank" class="rf-share-btn rf-wa">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode($user->getReferralLink()) }}&text={{ urlencode('Join TopTrade and earn daily!') }}"
                        target="_blank" class="rf-share-btn rf-tg">
                        <i class="bi bi-telegram"></i> Telegram
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode('Earn daily with TopTrade! '.$user->getReferralLink()) }}"
                        target="_blank" class="rf-share-btn rf-tw">
                        <i class="bi bi-twitter-x"></i> Twitter
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="rf-right-col">

        {{-- How it works --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-info-circle-fill"></i> How It Works</span>
            </div>
            <div class="rf-steps">
                @foreach([
                    ['icon'=>'bi-share-fill',      'color'=>'var(--accent)', 'title'=>'Share Your Link',   'desc'=>'Send your link to friends'],
                    ['icon'=>'bi-person-plus-fill', 'color'=>'var(--green)',  'title'=>'Friend Registers',  'desc'=>'They sign up with your link'],
                    ['icon'=>'bi-box-seam-fill',    'color'=>'var(--blue)',   'title'=>'They Buy Package',  'desc'=>'Any investment package'],
                    ['icon'=>'bi-cash-coin',        'color'=>'var(--gold)',   'title'=>'You Earn 10%',      'desc'=>'Instant wallet credit'],
                ] as $i => $s)
                <div class="rf-step {{ $i < 3 ? 'rf-step-border' : '' }}">
                    <div class="rf-step-ico" style="color:{{ $s['color'] }};background:rgba(0,0,0,0.2);">
                        <i class="bi {{ $s['icon'] }}"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="rf-step-title">{{ $s['title'] }}</div>
                        <div class="rf-step-desc">{{ $s['desc'] }}</div>
                    </div>
                    <div class="rf-step-num">{{ $i+1 }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Commission Tiers --}}
        @if($generationSettings->count() > 0)
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-layers-fill"></i> Commission Tiers</span>
            </div>
            <div>
                @foreach($generationSettings as $gs)
                @if($gs->is_active)
                <div class="rf-tier">
                    <div>
                        <div style="font-size:0.82rem;font-weight:700;">Gen {{ $gs->generation }}</div>
                        <div style="font-size:0.68rem;color:var(--muted);">{{ $gs->label }}</div>
                    </div>
                    <span class="rf-tier-pct">{{ $gs->commission_rate }}%</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

{{-- REFERRALS TABLE --}}
<div class="s-card" style="margin-bottom:20px;">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-people-fill"></i> My Referrals</span>
        <span style="font-size:0.72rem;color:var(--muted);">{{ $referrals->total() }} total</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th class="rf-hide-sm">Joined</th>
                    <th>Status</th>
                    <th style="text-align:right">Earned</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $ref)
                <tr>
                    <td style="color:var(--muted);font-size:0.72rem;width:36px;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="rf-avatar">
                                {{ strtoupper(substr($ref->referred->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.85rem;">{{ $ref->referred->name }}</div>
                                <div style="font-size:0.68rem;color:var(--muted);">{{ $ref->referred->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="rf-hide-sm">
                        <div style="font-size:0.78rem;">{{ $ref->activated_at?->format('d M Y') ?? '—' }}</div>
                        @if($ref->activated_at)
                        <div style="font-size:0.65rem;color:var(--muted);">{{ $ref->activated_at->diffForHumans() }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="s-pill {{ $ref->status === 'active' ? 'approved' : ($ref->status === 'pending' ? 'pending' : 'inactive') }}">
                            {{ ucfirst($ref->status) }}
                        </span>
                    </td>
                    <td style="text-align:right">
                        <span class="t-reward">${{ number_format($ref->earnings->sum('amount'), 2) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state" style="padding:40px 20px;">
                            <i class="bi bi-people"></i>
                            <p>No referrals yet</p>
                            <div style="margin-top:12px;">
                                <button onclick="copyLink()" class="cy-hbtn primary">
                                    <i class="bi bi-share-fill"></i> Share Your Link
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($referrals->hasPages())
    <div style="padding:12px 16px;border-top:1px solid var(--border);">{{ $referrals->links() }}</div>
    @endif
</div>

{{-- COMMISSION HISTORY --}}
<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-cash-stack"></i> Commission History</span>
        <span style="font-size:0.72rem;color:var(--muted);">{{ $earnings->total() }} records</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Date</th>
                    <th class="rf-hide-sm">Gen</th>
                    <th class="rf-hide-sm">Rate</th>
                    <th style="text-align:right">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($earnings as $earn)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="rf-avatar-sm">
                                {{ strtoupper(substr($earn->referred->name ?? 'U', 0, 1)) }}
                            </div>
                            <span style="font-weight:600;font-size:0.82rem;">{{ $earn->referred->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:0.78rem;">{{ $earn->created_at->format('d M Y') }}</div>
                        <div style="font-size:0.65rem;color:var(--muted);">{{ $earn->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="rf-hide-sm">
                        <span style="font-size:0.72rem;background:rgba(0,0,0,0.2);border:1px solid var(--border);border-radius:99px;padding:2px 8px;color:var(--muted);">
                            Gen {{ $earn->generation }}
                        </span>
                    </td>
                    <td class="rf-hide-sm" style="font-size:0.78rem;color:var(--muted);">
                        {{ number_format($earn->commission_rate, 2) }}%
                    </td>
                    <td style="text-align:right">
                        <span class="t-reward">+${{ number_format($earn->amount, 2) }}</span>
                    </td>
                    <td>
                        <span class="s-pill {{ $earn->status === 'paid' ? 'approved' : ($earn->status === 'pending' ? 'pending' : 'rejected') }}">
                            {{ ucfirst($earn->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state" style="padding:40px 20px;">
                            <i class="bi bi-cash-stack"></i>
                            <p>No commissions yet — they appear when your referrals make purchases</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($earnings->hasPages())
    <div style="padding:12px 16px;border-top:1px solid var(--border);">{{ $earnings->links() }}</div>
    @endif
</div>

@endsection

@push('scripts')
<style>
/* ── REFERRAL PAGE ── */
.rf-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 20px;
    margin-bottom: 24px;
    align-items: start;
}
.rf-right-col { display: flex; flex-direction: column; gap: 16px; }
.rf-comm-badge {
    font-size: 0.7rem; color: var(--accent);
    background: rgba(0,245,212,0.08);
    border: 1px solid rgba(0,245,212,0.2);
    border-radius: 99px; padding: 3px 10px;
    white-space: nowrap;
}
.rf-link-body { padding: 18px 20px; }
.rf-input-row  { display: flex; }
.rf-input {
    flex: 1; background: var(--card2) !important;
    border: 1px solid var(--border2) !important;
    border-right: none !important;
    border-radius: 9px 0 0 9px !important;
    padding: 10px 14px !important;
    color: var(--muted) !important;
    font-size: 0.78rem !important;
    font-family: 'DM Sans', sans-serif !important;
    outline: none; min-width: 0;
}
.rf-copy-btn {
    padding: 10px 16px; background: var(--accent); color: #000;
    border: none; border-radius: 0 9px 9px 0;
    font-size: 0.82rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer; display: flex; align-items: center; gap: 6px;
    white-space: nowrap; flex-shrink: 0; transition: all 0.2s;
}
.rf-copy-btn:hover { opacity: 0.9; }
.rf-copy-btn.copied { background: var(--green); }
.rf-code-box {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--card2); border: 1px solid var(--border);
    border-radius: 9px; padding: 10px 14px; margin-bottom: 14px;
    gap: 10px;
}
.rf-code-lbl { font-size: 0.65rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 3px; }
.rf-code-val { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem; color: var(--accent); letter-spacing: 3px; }
.rf-code-copy {
    background: rgba(0,0,0,0.2); border: 1px solid var(--border2);
    border-radius: 8px; padding: 7px 12px; color: var(--muted);
    cursor: pointer; font-size: 0.75rem; font-family: 'DM Sans', sans-serif;
    transition: all 0.2s; white-space: nowrap; flex-shrink: 0;
}
.rf-code-copy:hover { border-color: var(--accent); color: var(--accent); }
.rf-comm-note {
    display: flex; align-items: center; gap: 14px;
    background: rgba(0,245,212,0.05);
    border: 1px solid rgba(0,245,212,0.15);
    border-radius: 10px; padding: 12px 14px; margin-bottom: 14px;
    font-size: 0.8rem; color: var(--muted); line-height: 1.6;
}
.rf-comm-pct {
    font-family: 'Syne', sans-serif; font-size: 1.8rem;
    font-weight: 800; color: var(--accent); flex-shrink: 0; line-height: 1;
}
.rf-share-row { display: flex; gap: 8px; flex-wrap: wrap; }
.rf-share-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 7px 12px; border-radius: 99px;
    font-size: 0.75rem; font-weight: 600;
    text-decoration: none; transition: all 0.2s; flex-shrink: 0;
}
.rf-wa { border: 1px solid rgba(34,197,94,0.3);  background: rgba(34,197,94,0.08);  color: #22c55e; }
.rf-wa:hover { background: rgba(34,197,94,0.15); color: #22c55e; }
.rf-tg { border: 1px solid rgba(14,165,233,0.3); background: rgba(14,165,233,0.08); color: #0ea5e9; }
.rf-tg:hover { background: rgba(14,165,233,0.15); color: #0ea5e9; }
.rf-tw { border: 1px solid var(--border2); background: var(--card2); color: var(--muted); }
.rf-tw:hover { color: var(--text); border-color: var(--border); }
/* Steps */
.rf-steps { padding: 8px 0; }
.rf-step {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 16px;
}
.rf-step-border { border-bottom: 1px solid var(--border); }
.rf-step-ico {
    width: 30px; height: 30px; border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.08);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.88rem; flex-shrink: 0;
}
.rf-step-title { font-weight: 700; font-size: 0.82rem; margin-bottom: 2px; }
.rf-step-desc  { font-size: 0.72rem; color: var(--muted); }
.rf-step-num {
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.6rem; font-weight: 800; color: var(--muted); flex-shrink: 0;
}
/* Tier */
.rf-tier {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; border-bottom: 1px solid var(--border);
}
.rf-tier:last-child { border-bottom: none; }
.rf-tier-pct { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem; color: var(--accent); }
/* Avatars */
.rf-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), #6366f1);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-weight: 800;
    font-size: 0.85rem; color: #000; flex-shrink: 0;
}
.rf-avatar-sm {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(0,0,0,0.25); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif; font-size: 0.72rem; font-weight: 800;
    flex-shrink: 0;
}
/* Hide columns */
.rf-hide-sm {}

/* ── RESPONSIVE ── */
@media(max-width: 900px) {
    .rf-grid {
        grid-template-columns: 1fr !important;
    }
}
@media(max-width: 768px) {
    .rf-hide-sm { display: none !important; }
    .rf-link-body { padding: 14px; }
    .rf-comm-note { flex-direction: column; gap: 8px; }
    .rf-comm-pct  { font-size: 1.4rem; }
    .rf-code-box  { flex-direction: column; align-items: flex-start; gap: 8px; }
    .rf-code-copy { width: 100%; justify-content: center; }
    .rf-share-row { gap: 6px; }
    .rf-share-btn { font-size: 0.7rem; padding: 6px 10px; }
    .stats-row    { grid-template-columns: 1fr 1fr; }
}
@media(max-width: 480px) {
    .rf-comm-badge { display: none; }
    .rf-input      { font-size: 0.72rem !important; padding: 8px 10px !important; }
    .rf-copy-btn   { padding: 8px 12px; font-size: 0.75rem; }
    .rf-code-val   { font-size: 0.95rem; letter-spacing: 2px; }
    .stats-row     { grid-template-columns: 1fr 1fr; gap: 8px; }
    .stat-card     { padding: 12px; }
    .stat-card-val { font-size: 1.2rem; }
}
</style>
<script>
function copyLink() {
    const val = document.getElementById('referralLink').value;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(val);
    } else {
        const inp = document.getElementById('referralLink');
        inp.select(); document.execCommand('copy');
    }
    const btn  = document.getElementById('copyBtn');
    const icon = document.getElementById('copyIcon');
    const txt  = document.getElementById('copyText');
    btn.classList.add('copied');
    icon.className = 'bi bi-check2';
    txt.textContent = 'Copied!';
    setTimeout(() => {
        btn.classList.remove('copied');
        icon.className = 'bi bi-clipboard';
        txt.textContent = 'Copy';
    }, 2500);
}
function copyCode() {
    const code = '{{ $user->referral_code }}';
    if (navigator.clipboard) navigator.clipboard.writeText(code);
    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Code copied!', showConfirmButton:false, timer:1500 });
}
</script>
@endpush
