@extends('layouts.app')
@section('title', 'Referral Dashboard')

@section('css')
<style>
/* ═══════════════════════════════════════════
   REFERRAL PAGE — Cyberpunk New Layout
═══════════════════════════════════════════ */

/* ── 2-COL HERO ── */
.rf-hero {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.1rem;
    margin-bottom: 1.5rem;
}
@media(max-width:1100px){ .rf-hero { grid-template-columns:1fr; } }

/* LEFT — link card */
.rf-link-card {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
}
.rf-link-card::before {
    content: '';
    position: absolute; top:0; left:0; right:0; height:2px;
    background: linear-gradient(90deg, transparent, var(--cyan), var(--blue), transparent);
}
.rf-link-inner { padding: 1.5rem 1.5rem 1.25rem; }
.rf-link-eyebrow {
    font-family:'Orbitron',monospace; font-size:.48rem; letter-spacing:4px;
    text-transform:uppercase; color:rgba(0,245,255,.5); margin-bottom:.35rem;
}
.rf-link-title {
    font-family:'Orbitron',monospace; font-size:1rem; font-weight:900;
    color:#fff; letter-spacing:1px; margin-bottom:1rem;
}
.rf-link-title span { color:var(--cyan); }

/* input row */
.rf-input-row {
    display: flex; gap: 0; margin-bottom: .85rem;
}
.rf-input {
    flex: 1;
    background: var(--surface2) !important;
    border: 1px solid var(--border) !important;
    border-right: none !important;
    border-radius: 0 !important;
    color: var(--text-dim) !important;
    font-family: 'Orbitron', monospace !important;
    font-size: .58rem !important;
    letter-spacing: 1px;
    padding: .75rem 1rem !important;
}
.rf-input:focus { outline: none; border-color: var(--cyan) !important; box-shadow: none !important; }
.rf-copy-btn {
    display: flex; align-items: center; gap: .4rem;
    font-family:'Orbitron',monospace; font-size:.58rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase;
    padding: .75rem 1.25rem; cursor: pointer; border: none; flex-shrink: 0;
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black);
    clip-path: polygon(0 0, 100% 0, calc(100% - 6px) 100%, 0 100%);
    transition: all .25s; position:relative; overflow:hidden;
}
.rf-copy-btn::after { content:''; position:absolute; inset:0; background:rgba(255,255,255,.12); transform:scaleX(0); transform-origin:left; transition:transform .25s; }
.rf-copy-btn:hover::after { transform:scaleX(1); }
.rf-copy-btn:hover { box-shadow:var(--glow-cyan-lg); }

/* commission note */
.rf-comm-note {
    display: flex; align-items: center; gap: .6rem;
    background: rgba(0,245,255,.05);
    border: 1px solid rgba(0,245,255,.18);
    padding: .65rem 1rem;
}
.rf-comm-pct {
    font-family:'Orbitron',monospace; font-size:1.1rem; font-weight:900;
    color:var(--cyan); text-shadow:var(--glow-cyan); flex-shrink:0;
}
.rf-comm-desc { font-family:'Rajdhani',sans-serif; font-size:.85rem; color:var(--text-muted); line-height:1.4; }
.rf-comm-desc strong { color:#fff; font-family:'Orbitron',monospace; font-size:.6rem; letter-spacing:.5px; }

/* social share strip */
.rf-share-strip {
    border-top: 1px solid var(--border);
    padding: .85rem 1.5rem;
    background: rgba(0,0,0,.2);
    display: flex; align-items: center; gap: .65rem; flex-wrap: wrap;
}
.rf-share-lbl { font-family:'Orbitron',monospace; font-size:.46rem; letter-spacing:3px; text-transform:uppercase; color:var(--text-muted); margin-right:.25rem; flex-shrink:0; }
.rf-share-btn {
    display: inline-flex; align-items: center; gap: .35rem;
    font-family:'Orbitron',monospace; font-size:.5rem; font-weight:700; letter-spacing:1px; text-transform:uppercase;
    padding: .32rem .75rem; border: 1px solid var(--border);
    color:var(--text-dim); background:var(--surface2); text-decoration:none;
    clip-path: polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
    transition: all .2s;
}
.rf-share-btn:hover { color:var(--cyan); border-color:var(--cyan); background:rgba(0,245,255,.05); }

/* RIGHT — stat column */
.rf-stats-col { display: flex; flex-direction: column; gap: .75rem; }

.rf-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    display: flex; align-items: center; gap: 1rem;
    padding: 1rem 1.25rem;
    position: relative; overflow: hidden;
    transition: border-color .3s, transform .3s;
    clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
}
.rf-stat-card:hover { border-color:var(--border-bright); transform:translateX(4px); }
.rf-stat-card::before {
    content: '';
    position: absolute; top:0; left:0; bottom:0; width:3px;
    background: var(--sc, var(--cyan));
}

.rf-stat-ico {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.rf-stat-val {
    font-family:'Orbitron',monospace; font-size:1.2rem; font-weight:900;
    line-height:1; display:block; margin-bottom:.2rem;
}
.rf-stat-lbl {
    font-family:'Orbitron',monospace; font-size:.44rem; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted);
}
.rf-stat-badge {
    margin-left: auto; flex-shrink: 0;
    font-family:'Orbitron',monospace; font-size:.44rem; font-weight:700; letter-spacing:1px; text-transform:uppercase;
    padding: .18rem .55rem; border: 1px solid;
    clip-path: polygon(3px 0,100% 0,calc(100% - 3px) 100%,0 100%);
}

/* ── SECTION LABEL ── */
.rf-sec { font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:4px; text-transform:uppercase; color:var(--text-muted); display:flex; align-items:center; gap:.75rem; margin-bottom:1rem; margin-top:1.5rem; }
.rf-sec::after { content:''; flex:1; height:1px; background:linear-gradient(90deg,var(--border),transparent); }

/* ── TABLES ── */
.rf-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative; overflow: hidden;
    margin-bottom: 1.25rem;
}
.rf-panel::before {
    content:'';
    position:absolute; top:0; left:0; right:0; height:2px;
    background:linear-gradient(90deg, transparent, var(--cyan), transparent);
}
.rf-panel-hd {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.25rem;
    background:var(--surface2);
    border-bottom:1px solid var(--border);
}
.rf-panel-title {
    font-family:'Orbitron',monospace; font-size:.65rem; font-weight:700; letter-spacing:2px; text-transform:uppercase;
    color:var(--cyan); margin:0; display:flex; align-items:center; gap:.5rem;
}
.rf-count {
    font-family:'Orbitron',monospace; font-size:.5rem; letter-spacing:1.5px; text-transform:uppercase;
    color:var(--text-muted); border:1px solid var(--border); padding:.22rem .65rem;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
}

/* referral list — custom rows, NOT table */
.rf-list {}
.rf-row {
    display: grid;
    grid-template-columns: 32px 1fr 1fr auto auto;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1.25rem;
    border-bottom: 1px solid var(--border);
    transition: background .2s;
}
.rf-row:last-child { border-bottom:none; }
.rf-row:hover { background:rgba(0,245,255,.025); }
@media(max-width:767px){ .rf-row { grid-template-columns:32px 1fr auto; } .rf-row .rf-col-email,.rf-row .rf-col-date { display:none; } }

.rf-row-num { font-family:'Orbitron',monospace; font-size:.58rem; color:var(--text-muted); text-align:center; }

.rf-user-avatar {
    width:32px; height:32px; border-radius:50%;
    background:linear-gradient(135deg,var(--blue),rgba(0,245,255,.4));
    display:flex; align-items:center; justify-content:center;
    font-family:'Orbitron',monospace; font-size:.62rem; font-weight:900; color:#fff;
    flex-shrink:0; border:1px solid var(--border);
}
.rf-col-user { display:flex; align-items:center; gap:.65rem; }
.rf-user-name  { font-family:'Orbitron',monospace; font-size:.65rem; font-weight:700; color:#fff; display:block; }
.rf-user-email { font-family:'Rajdhani',sans-serif; font-size:.78rem; color:var(--text-muted); display:block; }
.rf-col-email  { font-family:'Rajdhani',sans-serif; font-size:.82rem; color:var(--text-muted); }
.rf-col-date   { font-family:'Orbitron',monospace; font-size:.58rem; color:var(--text-dim); }

.rf-badge {
    display:inline-flex; align-items:center; gap:.28rem;
    font-family:'Orbitron',monospace; font-size:.46rem; font-weight:700; letter-spacing:1px; text-transform:uppercase;
    padding:.22rem .6rem; border:1px solid;
    clip-path:polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
}
.rf-badge.active   { color:var(--cyan);       border-color:rgba(0,245,255,.4);   background:rgba(0,245,255,.07); }
.rf-badge.pending  { color:var(--warning);    border-color:rgba(251,191,36,.4);  background:rgba(251,191,36,.07); }
.rf-badge.inactive { color:var(--text-muted); border-color:var(--border);        background:var(--surface2); }

.rf-earn { font-family:'Orbitron',monospace; font-size:.75rem; font-weight:900; color:var(--cyan); }

/* commission rows */
.rf-comm-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto auto auto;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1.25rem;
    border-bottom: 1px solid var(--border);
    transition: background .2s;
}
.rf-comm-row:last-child { border-bottom:none; }
.rf-comm-row:hover { background:rgba(0,245,255,.025); }
@media(max-width:767px){ .rf-comm-row { grid-template-columns:1fr auto auto; } .rf-comm-row .rf-col-rate { display:none; } }

.rf-from-name { font-family:'Orbitron',monospace; font-size:.65rem; font-weight:700; color:#fff; display:block; }
.rf-from-date { font-family:'Rajdhani',sans-serif; font-size:.78rem; color:var(--text-muted); display:block; }
.rf-rate { font-family:'Orbitron',monospace; font-size:.62rem; color:var(--text-dim); }
.rf-amount { font-family:'Orbitron',monospace; font-size:.8rem; font-weight:900; color:var(--cyan); }

/* empty */
.rf-empty { text-align:center; padding:3rem 1.5rem; }
.rf-empty i { font-size:2.5rem; color:var(--cyan); opacity:.12; display:block; margin-bottom:.75rem; }
.rf-empty p { font-family:'Orbitron',monospace; font-size:.62rem; color:var(--text-muted); margin:0; }

/* pagination override */
.rf-panel .pagination { margin:.75rem 1.25rem .85rem; justify-content:flex-end; gap:3px; }

@media(max-width:575px){
    .rf-link-inner { padding:1.1rem; }
    .rf-share-strip { padding:.75rem 1.1rem; }
    .rf-stat-val { font-size:.95rem; }
}
</style>
@endsection

@section('content')
@include('includes.header', ['pageTitle' => 'Referral Dashboard'])

{{-- ══════════════════════
   HERO — Link + Stats
══════════════════════ --}}
<div class="rf-hero">

    {{-- LEFT: referral link --}}
    <div class="rf-link-card">
        <div class="rf-link-inner">
            <div class="rf-link-eyebrow">// Referral Programme</div>
            <div class="rf-link-title">Your <span>Referral</span> Link</div>

            <div class="rf-input-row">
                <input type="text" id="referralLink" class="rf-input form-control"
                       value="{{ $user->getReferralLink() }}" readonly>
                <button class="rf-copy-btn"
                    onclick="confirmAction({
                        title: 'Copy referral link?',
                        text: 'This link will be copied to clipboard',
                        icon: 'question',
                        confirmButtonText: 'Copy',
                        onConfirm: () => {
                            copyLink();
                            Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Link copied!', showConfirmButton:false, timer:1500 });
                        }
                    })">
                    <i class="bi bi-clipboard-check"></i> Copy
                </button>
            </div>

            <div class="rf-comm-note">
                <div class="rf-comm-pct">10%</div>
                <div class="rf-comm-desc">
                    <strong>Commission Rate</strong><br>
                    Earn 10% commission on every purchase made by users you refer — credited instantly to your wallet
                </div>
            </div>
        </div>

        <div class="rf-share-strip">
            <span class="rf-share-lbl">Share via</span>
            <a href="https://wa.me/?text={{ urlencode('Join me on toptrade and start earning! '.$user->getReferralLink()) }}"
               target="_blank" class="rf-share-btn"><i class="bi bi-whatsapp"></i> WhatsApp</a>
            <a href="https://t.me/share/url?url={{ urlencode($user->getReferralLink()) }}&text={{ urlencode('Join toptrade and earn daily!') }}"
               target="_blank" class="rf-share-btn"><i class="bi bi-telegram"></i> Telegram</a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Earn daily with toptrade! '.$user->getReferralLink()) }}"
               target="_blank" class="rf-share-btn"><i class="bi bi-twitter-x"></i> Twitter</a>
            <button class="rf-share-btn" onclick="copyLink(); alert('Link copied!')">
                <i class="bi bi-link-45deg"></i> Copy Link
            </button>
        </div>
    </div>

    {{-- RIGHT: stats --}}
    <div class="rf-stats-col">
        <div class="rf-stat-card" style="--sc:#00f5ff">
            <div class="rf-stat-ico" style="background:rgba(0,245,255,.1);color:var(--cyan);border:1px solid rgba(0,245,255,.2)">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span class="rf-stat-val" style="color:var(--cyan)">{{ $stats['total_referrals'] }}</span>
                <span class="rf-stat-lbl">Total Referrals</span>
            </div>
            <span class="rf-stat-badge" style="color:var(--cyan);border-color:rgba(0,245,255,.3);background:rgba(0,245,255,.07)">All Time</span>
        </div>
        <div class="rf-stat-card" style="--sc:#34d399">
            <div class="rf-stat-ico" style="background:rgba(52,211,153,.1);color:#34d399;border:1px solid rgba(52,211,153,.2)">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <span class="rf-stat-val" style="color:#34d399">{{ $stats['active_referrals'] }}</span>
                <span class="rf-stat-lbl">Active Referrals</span>
            </div>
            <span class="rf-stat-badge" style="color:#34d399;border-color:rgba(52,211,153,.3);background:rgba(52,211,153,.07)">Active</span>
        </div>
        <div class="rf-stat-card" style="--sc:var(--warning)">
            <div class="rf-stat-ico" style="background:rgba(251,191,36,.1);color:var(--warning);border:1px solid rgba(251,191,36,.2)">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <span class="rf-stat-val" style="color:var(--warning)">{{ $stats['pending_referrals'] }}</span>
                <span class="rf-stat-lbl">Pending</span>
            </div>
            <span class="rf-stat-badge" style="color:var(--warning);border-color:rgba(251,191,36,.3);background:rgba(251,191,36,.07)">Awaiting</span>
        </div>
        <div class="rf-stat-card" style="--sc:#60a5fa">
            <div class="rf-stat-ico" style="background:rgba(96,165,250,.1);color:#60a5fa;border:1px solid rgba(96,165,250,.2)">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div>
                <span class="rf-stat-val" style="color:#60a5fa">${{ number_format($stats['total_earnings'],2) }}</span>
                <span class="rf-stat-lbl">Total Earned</span>
            </div>
            <span class="rf-stat-badge" style="color:#60a5fa;border-color:rgba(96,165,250,.3);background:rgba(96,165,250,.07)">Commissions</span>
        </div>
    </div>

</div>

{{-- ══════════════════════
   REFERRALS LIST
══════════════════════ --}}
<div class="rf-panel">
    <div class="rf-panel-hd">
        <h2 class="rf-panel-title"><i class="bi bi-people-fill"></i> My Referrals</h2>
        <span class="rf-count">{{ $referrals->total() }} total</span>
    </div>

    <div class="rf-list">
        @forelse($referrals as $ref)
        <div class="rf-row">
            <div class="rf-row-num">{{ $loop->iteration }}</div>

            <div class="rf-col-user">
                <div class="rf-user-avatar">{{ strtoupper(substr($ref->referred->name, 0, 1)) }}</div>
                <div>
                    <span class="rf-user-name">{{ $ref->referred->name }}</span>
                    <span class="rf-user-email">{{ $ref->referred->email }}</span>
                </div>
            </div>

            <div class="rf-col-email">{{ $ref->referred->email }}</div>

            <div class="rf-col-date">
                {{ $ref->activated_at?->format('d M Y') ?? '—' }}
            </div>

            <span class="rf-badge {{ $ref->status === 'active' ? 'active' : ($ref->status === 'pending' ? 'pending' : 'inactive') }}">
                <i class="bi bi-circle-fill" style="font-size:.38rem"></i>
                {{ ucfirst($ref->status) }}
            </span>

            <span class="rf-earn">${{ number_format($ref->earnings->sum('amount'),2) }}</span>
        </div>
        @empty
        <div class="rf-empty">
            <i class="bi bi-people"></i>
            <p>No referrals yet — share your link to start earning</p>
        </div>
        @endforelse
    </div>

    {{ $referrals->links('pagination::bootstrap-5') }}
</div>

{{-- ══════════════════════
   COMMISSION HISTORY
══════════════════════ --}}
<div class="rf-panel">
    <div class="rf-panel-hd">
        <h2 class="rf-panel-title"><i class="bi bi-cash-stack"></i> Commission History</h2>
        <span class="rf-count">{{ $earnings->total() }} records</span>
    </div>

    <div class="rf-list">
        @forelse($earnings as $earning)
        <div class="rf-comm-row">
            <div>
                <span class="rf-from-name">{{ $earning->referral->referred->name }}</span>
                <span class="rf-from-date">{{ $earning->created_at->format('d M Y · H:i') }}</span>
            </div>

            <div class="rf-col-rate">
                <span class="rf-rate">{{ number_format($earning->commission_rate,2) }}% commission</span>
            </div>

            <span class="rf-amount">${{ number_format($earning->amount,2) }}</span>

            <span class="rf-badge {{ $earning->status === 'paid' ? 'active' : ($earning->status === 'pending' ? 'pending' : 'inactive') }}">
                <i class="bi bi-circle-fill" style="font-size:.38rem"></i>
                {{ ucfirst($earning->status) }}
            </span>
        </div>
        @empty
        <div class="rf-empty">
            <i class="bi bi-cash-stack"></i>
            <p>No commissions yet — they'll appear here when your referrals make purchases</p>
        </div>
        @endforelse
    </div>

    {{ $earnings->links('pagination::bootstrap-5') }}
</div>

@endsection

@push('scripts')
<script>
function copyLink() {
    const input = document.getElementById('referralLink');
    input.select();
    input.setSelectionRange(0, 99999);
    if (navigator.clipboard) {
        navigator.clipboard.writeText(input.value);
    } else {
        document.execCommand('copy');
    }
}
</script>
@endpush
