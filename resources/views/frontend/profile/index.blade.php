@extends('layouts.app')
@section('title', 'My Profile')

@section('css')
<style>
/* ═══════════════════════════════════════════════════════
   PROFILE PAGE — BLACK · CYAN · BLUE ONLY
   No greens / ambers / reds — strict 3-color palette
═══════════════════════════════════════════════════════ */

/* ── PALETTE OVERRIDES (local) ── */
:root {
    --p-black:   #000000;
    --p-surface: #050c18;
    --p-surf2:   #080f1f;
    --p-surf3:   #0c1628;
    --p-border:  rgba(0,245,255,0.1);
    --p-border2: rgba(0,245,255,0.22);
    --p-cyan:    #00f5ff;
    --p-blue:    #0047ff;
    --p-blue2:   #1a63ff;
    --p-text:    rgba(255,255,255,0.85);
    --p-dim:     rgba(255,255,255,0.5);
    --p-muted:   rgba(255,255,255,0.28);
    --p-glow:    0 0 24px rgba(0,245,255,0.3), 0 0 48px rgba(0,245,255,0.1);
    --p-glow-blue: 0 0 24px rgba(0,71,255,0.4);
}

.pf-page { display: flex; flex-direction: column; gap: 1.25rem; }

/* ════════════════════════
   HERO STRIP
════════════════════════ */
.pf-hero {
    background: var(--p-surface);
    border: 1px solid var(--p-border);
    border-left: 3px solid var(--p-cyan);
    position: relative; overflow: hidden;
}
.pf-hero::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, var(--p-cyan), transparent);
}
.pf-hero::after {
    content: '';
    position: absolute; top: -80px; right: -80px;
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(0,71,255,0.12), transparent 65%);
    pointer-events: none;
}
.pf-hero-inner {
    display: flex; align-items: center; gap: 1.5rem;
    padding: 1.5rem; flex-wrap: wrap; position: relative; z-index: 1;
}

/* avatar */
.pf-avatar-wrap { position: relative; flex-shrink: 0; }
.pf-avatar {
    width: 88px; height: 88px; object-fit: cover;
    border: 2px solid var(--p-border2); display: block;
    clip-path: polygon(6px 0,100% 0,100% calc(100% - 6px),calc(100% - 6px) 100%,0 100%,0 6px);
    box-shadow: 0 0 16px rgba(0,245,255,0.15);
}
.pf-avatar-btn {
    position: absolute; bottom: -4px; right: -4px;
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; cursor: pointer;
    background: var(--p-cyan); color: #000; border: none;
    clip-path: polygon(4px 0,100% 0,100% calc(100% - 4px),calc(100% - 4px) 100%,0 100%,0 4px);
    transition: all 0.2s;
}
.pf-avatar-btn:hover { box-shadow: var(--p-glow); transform: scale(1.08); }

/* name / meta */
.pf-hero-info { flex: 1; min-width: 0; }
.pf-name {
    font-family: 'Orbitron', monospace;
    font-size: 1.05rem; font-weight: 900; color: #fff;
    letter-spacing: 1px; margin-bottom: 0.45rem; line-height: 1.2;
    display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
}
.pf-verified {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.42rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
    padding: 0.18rem 0.5rem;
    border: 1px solid rgba(0,245,255,0.35);
    color: var(--p-cyan); background: rgba(0,245,255,0.07);
    clip-path: polygon(3px 0,100% 0,calc(100% - 3px) 100%,0 100%);
}
.pf-meta { display: flex; flex-wrap: wrap; gap: 0.5rem 1.25rem; }
.pf-meta-item {
    display: flex; align-items: center; gap: 0.35rem;
    font-family: 'Rajdhani', sans-serif;
    font-size: 0.85rem; color: var(--p-dim);
}
.pf-meta-item i { color: var(--p-cyan); font-size: 0.8rem; }

/* balance */
.pf-hero-bal { flex-shrink: 0; text-align: right; }
.pf-bal-lbl {
    font-family: 'Orbitron', monospace;
    font-size: 0.43rem; letter-spacing: 3px; text-transform: uppercase;
    color: var(--p-muted); display: block; margin-bottom: 0.3rem;
}
.pf-bal-val {
    font-family: 'Orbitron', monospace;
    font-size: 1.5rem; font-weight: 900; color: var(--p-cyan);
    text-shadow: 0 0 20px rgba(0,245,255,0.3); line-height: 1;
}
.pf-bal-sub { font-size: 0.72rem; color: var(--p-muted); margin-top: 0.3rem; display: block; }

/* ════════════════════════
   STAT CARDS
════════════════════════ */
.pf-stats {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 1px; background: var(--p-border);
    border: 1px solid var(--p-border);
}
@media(max-width:767px){ .pf-stats{ grid-template-columns:1fr 1fr; } }

.pf-stat {
    background: var(--p-surface);
    padding: 1.1rem 1.25rem;
    display: flex; align-items: center; gap: 0.85rem;
    position: relative; overflow: hidden; transition: background 0.25s;
}
.pf-stat:hover { background: rgba(0,245,255,0.03); }
.pf-stat::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: var(--sc, rgba(0,245,255,0.45));
    transform: scaleX(0); transition: transform 0.4s;
}
.pf-stat:hover::before { transform: scaleX(1); }

.pf-stat-icon {
    width: 40px; height: 40px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
    clip-path: polygon(5px 0,100% 0,100% calc(100% - 5px),calc(100% - 5px) 100%,0 100%,0 5px);
}
.pf-stat-body { flex: 1; min-width: 0; }
.pf-stat-lbl {
    font-family: 'Orbitron', monospace;
    font-size: 0.42rem; letter-spacing: 2.5px; text-transform: uppercase;
    color: var(--p-muted); display: block; margin-bottom: 0.25rem;
}
.pf-stat-val {
    font-family: 'Orbitron', monospace;
    font-size: 1.05rem; font-weight: 900; color: #fff; line-height: 1;
}

/* ════════════════════════
   PAGE GRID
════════════════════════ */
.pf-grid {
    display: grid; grid-template-columns: 300px 1fr;
    gap: 1.25rem; align-items: start;
}
@media(max-width:991px){ .pf-grid{ grid-template-columns:1fr; } }

/* ════════════════════════
   SHARED PANEL
════════════════════════ */
.pf-panel {
    background: var(--p-surface);
    border: 1px solid var(--p-border);
    overflow: hidden; margin-bottom: 1.25rem; position: relative;
}
.pf-panel::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,245,255,0.18), transparent);
}
.pf-panel:last-child { margin-bottom: 0; }

.pf-panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1.25rem;
    background: var(--p-surf2); border-bottom: 1px solid var(--p-border);
}
.pf-panel-title {
    font-family: 'Orbitron', monospace;
    font-size: 0.6rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: var(--p-cyan); margin: 0; display: flex; align-items: center; gap: 0.5rem;
}
.pf-panel-body { padding: 1.25rem; }

/* ════════════════════════
   FORM ELEMENTS
════════════════════════ */
.pf-field { margin-bottom: 1.1rem; }
.pf-field:last-of-type { margin-bottom: 0; }

.pf-label {
    font-family: 'Orbitron', monospace;
    font-size: 0.48rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: var(--p-dim); display: flex; align-items: center; gap: 0.4rem;
    margin-bottom: 0.45rem;
}
.pf-label i { color: var(--p-cyan); font-size: 0.75rem; }
.pf-label .req { color: #6b8cff; }

.pf-input-wrap { position: relative; }
.pf-input-icon {
    position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%);
    color: var(--p-cyan); font-size: 0.9rem; pointer-events: none; z-index: 2;
}

.pf-inp, .pf-sel, .pf-ta {
    width: 100%;
    background: var(--p-surf2) !important;
    border: 1px solid var(--p-border) !important;
    color: #fff !important;
    border-radius: 0 !important;
    font-family: 'Rajdhani', sans-serif;
    font-size: 0.95rem; font-weight: 500;
    padding: 0.6rem 0.85rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none; -webkit-appearance: none;
}
.pf-inp.has-icon { padding-left: 2.5rem; }
.pf-inp::placeholder, .pf-ta::placeholder { color: var(--p-muted) !important; font-size: 0.88rem; }
.pf-inp:focus, .pf-sel:focus, .pf-ta:focus {
    border-color: var(--p-cyan) !important;
    box-shadow: 0 0 0 2px rgba(0,245,255,0.1) !important;
}
.pf-inp.is-invalid, .pf-sel.is-invalid { border-color: #6b8cff !important; }
.pf-sel option { background: #050c18; color: #fff; }
.pf-ta { resize: vertical; min-height: 90px; }

.pf-error {
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; letter-spacing: 0.5px;
    color: #6b8cff; margin-top: 0.3rem;
    display: flex; align-items: center; gap: 0.35rem;
}

.pf-field-row {
    display: grid; gap: 0.85rem; grid-template-columns: 1fr 1fr;
    margin-bottom: 1.1rem;
}
@media(max-width:575px){ .pf-field-row{ grid-template-columns:1fr; } }
.pf-field-row .pf-field { margin-bottom: 0; }

/* ── BUTTONS ── */
.pf-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.6rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
    padding: 0.7rem 1.4rem; border: none; cursor: pointer; transition: all 0.25s;
    clip-path: polygon(6px 0,100% 0,calc(100% - 6px) 100%,0 100%);
    position: relative; overflow: hidden;
}
.pf-btn::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
    opacity: 0; transition: opacity 0.25s;
}
.pf-btn:hover::before { opacity: 1; }
.pf-btn:hover { transform: translateY(-1px); }

.pf-btn.primary {
    background: linear-gradient(135deg, var(--p-cyan), var(--p-blue));
    color: #000;
}
.pf-btn.primary:hover { box-shadow: var(--p-glow); color: #000; }

.pf-btn.w-full { width: 100%; justify-content: center; }

/* "Danger" actions use blue instead of red */
.pf-btn.critical {
    background: linear-gradient(135deg, var(--p-blue2), var(--p-blue));
    color: #fff;
}
.pf-btn.critical:hover { box-shadow: var(--p-glow-blue); }

/* ── DANGER ZONE (styled in blue) ── */
.pf-danger-zone {
    background: rgba(0,71,255,0.04);
    border: 1px solid rgba(0,71,255,0.2);
    border-left: 3px solid var(--p-blue);
    overflow: hidden;
}
.pf-danger-zone .pf-panel-head { background: rgba(0,71,255,0.07); }
.pf-danger-zone .pf-panel-title { color: #6b8cff; }
.pf-danger-desc {
    font-family: 'Rajdhani', sans-serif;
    font-size: 0.88rem; color: var(--p-dim); margin-bottom: 1rem; line-height: 1.6;
}

/* ════════════════════════
   DELETE MODAL
════════════════════════ */
#deleteAccountModal .modal-content {
    background: var(--p-surface) !important;
    border: 1px solid rgba(0,71,255,0.3) !important;
    border-top: 2px solid var(--p-blue) !important;
    border-radius: 0 !important;
}
#deleteAccountModal .modal-header {
    background: rgba(0,71,255,0.07) !important;
    border-bottom: 1px solid rgba(0,71,255,0.18) !important;
    padding: 1rem 1.5rem;
}
.del-modal-title {
    font-family: 'Orbitron', monospace;
    font-size: 0.62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
    color: #6b8cff; display: flex; align-items: center; gap: 0.5rem;
}
#deleteAccountModal .modal-body { background: var(--p-surface); padding: 1.5rem; }
#deleteAccountModal .modal-footer {
    background: var(--p-surf2);
    border-top: 1px solid var(--p-border) !important;
    padding: 1rem 1.5rem;
}

.del-warning {
    background: rgba(0,71,255,0.06);
    border: 1px solid rgba(0,71,255,0.25);
    border-left: 3px solid var(--p-blue);
    padding: 0.85rem 1rem; margin-bottom: 1.25rem;
    font-family: 'Rajdhani', sans-serif;
    font-size: 0.88rem; color: var(--p-dim);
    display: flex; align-items: flex-start; gap: 0.6rem;
}
.del-warning i { color: #6b8cff; flex-shrink: 0; margin-top: 0.1rem; }

.del-check-row {
    display: flex; align-items: flex-start; gap: 0.75rem; margin-top: 1rem;
    padding: 0.75rem;
    background: rgba(0,71,255,0.04); border: 1px solid rgba(0,71,255,0.15);
}
.del-check-row input[type="checkbox"] {
    appearance: none; -webkit-appearance: none;
    width: 16px; height: 16px; flex-shrink: 0;
    background: var(--p-surf2); border: 1px solid var(--p-border);
    cursor: pointer; margin-top: 0.15rem; transition: all 0.2s;
}
.del-check-row input[type="checkbox"]:checked {
    background: var(--p-blue); border-color: var(--p-blue);
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: center; background-size: 80%;
}
.del-check-row label {
    font-family: 'Rajdhani', sans-serif; font-size: 0.88rem; color: var(--p-dim); cursor: pointer;
}

.del-btn-cancel {
    font-family: 'Orbitron', monospace;
    font-size: 0.58rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
    padding: 0.6rem 1.25rem; background: var(--p-surf2);
    border: 1px solid var(--p-border); color: var(--p-dim); cursor: pointer;
    clip-path: polygon(5px 0,100% 0,calc(100% - 5px) 100%,0 100%);
    transition: all 0.2s;
}
.del-btn-cancel:hover { border-color: var(--p-cyan); color: var(--p-cyan); }

/* pulse */
.pf-pulse {
    width: 7px; height: 7px; border-radius: 50%; display: inline-block;
    background: var(--p-cyan); box-shadow: 0 0 6px var(--p-cyan);
    animation: pf-blink 2s infinite; flex-shrink: 0;
}
@keyframes pf-blink { 0%,100%{opacity:1}50%{opacity:.3} }

/* ── RESPONSIVE ── */
@media(max-width:575px){
    .pf-hero-inner { gap: 1rem; }
    .pf-avatar { width: 68px; height: 68px; }
    .pf-bal-val { font-size: 1.1rem; }
    .pf-name { font-size: 0.82rem; }
    .pf-panel-body { padding: 1rem; }
    .pf-hero-bal { display: none; }
}
</style>
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'My Profile'])

    <div class="pf-page">

        {{-- ══ HERO STRIP ══ --}}
        <div class="pf-hero">
            <div class="pf-hero-inner">

                <div class="pf-avatar-wrap">
                    <img src="{{ $user->avatar_url }}" alt="Avatar"
                        class="pf-avatar" id="avatarPreview">
                    <label for="avatarInput" class="pf-avatar-btn" title="Change avatar">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                    <form action="{{ route('profile.upload.avatar') }}" method="POST"
                        enctype="multipart/form-data" id="avatarForm">
                        @csrf
                        <input type="file" name="avatar" id="avatarInput"
                            accept="image/*" style="display:none;">
                    </form>
                </div>

                <div class="pf-hero-info">
                    <div class="pf-name">
                        {{ $user->full_name }}
                        @if($user->email_verified_at)
                            <span class="pf-verified">
                                <i class="bi bi-check-circle-fill"></i> Verified
                            </span>
                        @endif
                    </div>
                    <div class="pf-meta">
                        <span class="pf-meta-item">
                            <i class="bi bi-envelope-fill"></i> {{ $user->email }}
                        </span>
                        <span class="pf-meta-item">
                            <i class="bi bi-calendar-fill"></i> Member since {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="pf-hero-bal">
                    <span class="pf-bal-lbl">Wallet Balance</span>
                    <div class="pf-bal-val">${{ number_format($totalEarnings, 2) }}</div>
                    <span class="pf-bal-sub">USDT · Available</span>
                </div>

            </div>
        </div>

        {{-- ══ STAT CARDS ══ --}}
        <div class="pf-stats">
            <div class="pf-stat" style="--sc:rgba(0,245,255,0.5);">
                <div class="pf-stat-icon" style="color:var(--p-cyan);border:1px solid rgba(0,245,255,0.2);background:rgba(0,245,255,0.07);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="pf-stat-body">
                    <span class="pf-stat-lbl">Total Referrals</span>
                    <span class="pf-stat-val" style="color:var(--p-cyan);">{{ $user->total_referrals }}</span>
                </div>
            </div>
            <div class="pf-stat" style="--sc:rgba(0,71,255,0.7);">
                <div class="pf-stat-icon" style="color:var(--p-blue2);border:1px solid rgba(0,71,255,0.25);background:rgba(0,71,255,0.1);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="pf-stat-body">
                    <span class="pf-stat-lbl">Active Referrals</span>
                    <span class="pf-stat-val" style="color:var(--p-blue2);">{{ $user->getActiveReferralsCount() }}</span>
                </div>
            </div>
            <div class="pf-stat" style="--sc:rgba(0,245,255,0.5);">
                <div class="pf-stat-icon" style="color:var(--p-cyan);border:1px solid rgba(0,245,255,0.2);background:rgba(0,245,255,0.07);">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="pf-stat-body">
                    <span class="pf-stat-lbl">Referral Earnings</span>
                    <span class="pf-stat-val" style="color:var(--p-cyan);">${{ number_format($user->total_referral_earnings, 2) }}</span>
                </div>
            </div>
            <div class="pf-stat" style="--sc:rgba(0,71,255,0.7);">
                <div class="pf-stat-icon" style="color:var(--p-blue2);border:1px solid rgba(0,71,255,0.25);background:rgba(0,71,255,0.1);">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="pf-stat-body">
                    <span class="pf-stat-lbl">Age</span>
                    <span class="pf-stat-val" style="color:var(--p-blue2);">{{ $user->age ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- ══ MAIN GRID ══ --}}
        <div class="pf-grid">

            {{-- ════ LEFT: Social Links ════ --}}
            <div>
                <div class="pf-panel">
                    <div class="pf-panel-head">
                        <h3 class="pf-panel-title"><i class="bi bi-share-fill"></i> Social Links</h3>
                    </div>
                    <div class="pf-panel-body">
                        <form action="{{ route('profile.update.social') }}" method="POST">
                            @csrf

                            <div class="pf-field">
                                <label class="pf-label">Facebook</label>
                                <div class="pf-input-wrap">
                                    <span class="pf-input-icon"><i class="bi bi-facebook"></i></span>
                                    <input type="url" name="facebook_url" class="pf-inp has-icon"
                                        value="{{ $user->profile->facebook_url ?? '' }}"
                                        placeholder="https://facebook.com/...">
                                </div>
                            </div>

                            <div class="pf-field">
                                <label class="pf-label">Twitter / X</label>
                                <div class="pf-input-wrap">
                                    <span class="pf-input-icon"><i class="bi bi-twitter-x"></i></span>
                                    <input type="url" name="twitter_url" class="pf-inp has-icon"
                                        value="{{ $user->profile->twitter_url ?? '' }}"
                                        placeholder="https://twitter.com/...">
                                </div>
                            </div>

                            <div class="pf-field">
                                <label class="pf-label">Instagram</label>
                                <div class="pf-input-wrap">
                                    <span class="pf-input-icon"><i class="bi bi-instagram"></i></span>
                                    <input type="url" name="instagram_url" class="pf-inp has-icon"
                                        value="{{ $user->profile->instagram_url ?? '' }}"
                                        placeholder="https://instagram.com/...">
                                </div>
                            </div>

                            <div class="pf-field" style="margin-bottom:1.25rem;">
                                <label class="pf-label">LinkedIn</label>
                                <div class="pf-input-wrap">
                                    <span class="pf-input-icon"><i class="bi bi-linkedin"></i></span>
                                    <input type="url" name="linkedin_url" class="pf-inp has-icon"
                                        value="{{ $user->profile->linkedin_url ?? '' }}"
                                        placeholder="https://linkedin.com/in/...">
                                </div>
                            </div>

                            <button type="submit" class="pf-btn primary w-full">
                                <i class="bi bi-check-lg"></i> Save Social Links
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ════ RIGHT: Forms ════ --}}
            <div>

                {{-- Basic Information --}}
                <div class="pf-panel">
                    <div class="pf-panel-head">
                        <h3 class="pf-panel-title"><i class="bi bi-person-fill"></i> Basic Information</h3>
                    </div>
                    <div class="pf-panel-body">
                        <form action="{{ route('profile.update.basic') }}" method="POST">
                            @csrf

                            <div class="pf-field-row">
                                <div class="pf-field">
                                    <label class="pf-label">First Name <span class="req">*</span></label>
                                    <input type="text" name="name"
                                        class="pf-inp @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">Last Name</label>
                                    <input type="text" name="last_name"
                                        class="pf-inp @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="pf-field-row">
                                <div class="pf-field">
                                    <label class="pf-label">Email <span class="req">*</span></label>
                                    <div class="pf-input-wrap">
                                        <span class="pf-input-icon"><i class="bi bi-envelope-fill"></i></span>
                                        <input type="email" name="email"
                                            class="pf-inp has-icon @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    @error('email')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth"
                                        class="pf-inp @error('date_of_birth') is-invalid @enderror"
                                        value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                        style="color-scheme:dark;">
                                    @error('date_of_birth')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="pf-field" style="margin-bottom:1.25rem;">
                                <label class="pf-label">Gender</label>
                                <select name="gender" class="pf-sel @error('gender') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male"   {{ old('gender', $user->gender) == 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other"  {{ old('gender', $user->gender) == 'other'  ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="pf-btn primary">
                                <i class="bi bi-check-lg"></i> Update Basic Info
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="pf-panel">
                    <div class="pf-panel-head">
                        <h3 class="pf-panel-title"><i class="bi bi-telephone-fill"></i> Contact Information</h3>
                    </div>
                    <div class="pf-panel-body">
                        <form action="{{ route('profile.update.details') }}" method="POST">
                            @csrf

                            <div class="pf-field-row">
                                <div class="pf-field">
                                    <label class="pf-label">Phone</label>
                                    <div class="pf-input-wrap">
                                        <span class="pf-input-icon"><i class="bi bi-telephone-fill"></i></span>
                                        <input type="tel" name="phone" class="pf-inp has-icon"
                                            value="{{ old('phone', $user->profile->phone ?? '') }}">
                                    </div>
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">Country</label>
                                    <div class="pf-input-wrap">
                                        <span class="pf-input-icon"><i class="bi bi-globe2"></i></span>
                                        <input type="text" name="country" class="pf-inp has-icon"
                                            value="{{ old('country', $user->profile->country ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="pf-field-row">
                                <div class="pf-field">
                                    <label class="pf-label">State / Division</label>
                                    <input type="text" name="state" class="pf-inp"
                                        value="{{ old('state', $user->profile->state ?? '') }}">
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">City</label>
                                    <input type="text" name="city" class="pf-inp"
                                        value="{{ old('city', $user->profile->city ?? '') }}">
                                </div>
                            </div>

                            <div class="pf-field-row" style="grid-template-columns:2fr 1fr;">
                                <div class="pf-field">
                                    <label class="pf-label">Address</label>
                                    <input type="text" name="address" class="pf-inp"
                                        value="{{ old('address', $user->profile->address ?? '') }}">
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">Postal Code</label>
                                    <input type="text" name="postal_code" class="pf-inp"
                                        value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
                                </div>
                            </div>

                            <div class="pf-field">
                                <label class="pf-label">Occupation</label>
                                <div class="pf-input-wrap">
                                    <span class="pf-input-icon"><i class="bi bi-briefcase-fill"></i></span>
                                    <input type="text" name="occupation" class="pf-inp has-icon"
                                        value="{{ old('occupation', $user->profile->occupation ?? '') }}">
                                </div>
                            </div>

                            <div class="pf-field" style="margin-bottom:1.25rem;">
                                <label class="pf-label">Bio</label>
                                <textarea name="bio" class="pf-ta"
                                    placeholder="Tell us about yourself...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                            </div>

                            <button type="submit" class="pf-btn primary">
                                <i class="bi bi-check-lg"></i> Update Contact Info
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="pf-panel">
                    <div class="pf-panel-head">
                        <h3 class="pf-panel-title"><i class="bi bi-shield-lock-fill"></i> Change Password</h3>
                    </div>
                    <div class="pf-panel-body">
                        <form action="{{ route('profile.change.password') }}" method="POST">
                            @csrf

                            <div class="pf-field">
                                <label class="pf-label">Current Password <span class="req">*</span></label>
                                <div class="pf-input-wrap">
                                    <span class="pf-input-icon"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="current_password"
                                        class="pf-inp has-icon @error('current_password') is-invalid @enderror" required>
                                </div>
                                @error('current_password')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="pf-field-row">
                                <div class="pf-field">
                                    <label class="pf-label">New Password <span class="req">*</span></label>
                                    <div class="pf-input-wrap">
                                        <span class="pf-input-icon"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" name="new_password"
                                            class="pf-inp has-icon @error('new_password') is-invalid @enderror" required>
                                    </div>
                                    @error('new_password')<div class="pf-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">Confirm Password <span class="req">*</span></label>
                                    <div class="pf-input-wrap">
                                        <span class="pf-input-icon"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" name="new_password_confirmation"
                                            class="pf-inp has-icon" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="pf-btn primary" style="margin-top:0.15rem;">
                                <i class="bi bi-key-fill"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Danger Zone (blue-themed) --}}
                <div class="pf-panel pf-danger-zone">
                    <div class="pf-panel-head">
                        <h3 class="pf-panel-title"><i class="bi bi-exclamation-triangle-fill"></i> Danger Zone</h3>
                    </div>
                    <div class="pf-panel-body">
                        <p class="pf-danger-desc">
                            Permanently deletes your account and all associated data. This cannot be undone.
                        </p>
                        <button type="button" class="pf-btn critical"
                            data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash3-fill"></i> Delete My Account
                        </button>
                    </div>
                </div>

            </div>{{-- /right --}}
        </div>{{-- /grid --}}
    </div>{{-- /pf-page --}}

    {{-- ══ DELETE MODAL ══ --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('profile.delete') }}" method="POST" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header border-0">
                        <div class="del-modal-title">
                            <i class="bi bi-exclamation-triangle-fill"></i> Delete Account
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="del-warning">
                            <i class="bi bi-info-circle-fill"></i>
                            <span><strong>Warning:</strong> This is permanent. All earnings, packages and data will be removed.</span>
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Confirm your Password <span class="req">*</span></label>
                            <div class="pf-input-wrap">
                                <span class="pf-input-icon"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password"
                                    class="pf-inp has-icon" placeholder="Enter your password" required>
                            </div>
                        </div>

                        <div class="del-check-row">
                            <input type="checkbox" id="confirmDelete" required>
                            <label for="confirmDelete">I understand this cannot be reversed</label>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="del-btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> Cancel
                        </button>
                        <button type="submit" class="pf-btn critical">
                            <i class="bi bi-trash3-fill"></i> Delete Permanently
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        confirmAction({
            title: 'Upload new avatar?',
            text: 'Your profile picture will be updated.',
            icon: 'question',
            confirmButtonText: 'Upload',
            onConfirm: () => {
                const reader = new FileReader();
                reader.onload = e => { document.getElementById('avatarPreview').src = e.target.result; };
                reader.readAsDataURL(file);
                document.getElementById('avatarForm').submit();
            },
            onCancel: () => { e.target.value = ''; }
        });
    }
});

document.getElementById('deleteAccountForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const password = this.querySelector('input[name="password"]').value;
    if (!password) {
        Swal.fire({ icon: 'error', title: 'Password Required', text: 'Please enter your password.' });
        return;
    }
    confirmAction({
        title: 'Are you absolutely sure?',
        text: 'This will permanently delete your account and all data!',
        icon: 'warning',
        confirmButtonText: 'Yes, delete permanently!',
        confirmButtonColor: '#0047ff',
        onConfirm: () => { this.submit(); }
    });
});
</script>
@endpush
