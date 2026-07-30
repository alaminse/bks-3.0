@extends('layouts.app')
@section('title', 'Available Tasks')
@section('page-title', 'Tasks')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-lightning-charge-fill" style="color:var(--accent);font-size:1.2rem;"></i> Available Tasks</h1>
        <p>Complete tasks to earn rewards directly to your wallet</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('tasks.history') }}" class="cy-hbtn outline">
            <i class="bi bi-clock-history"></i> History
        </a>
    </div>
</div>

{{-- ── STATS ROW ── --}}
<div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-card-lbl">Earned Today</div>
        <div class="stat-card-val" style="color:var(--accent);" id="stat-earned">${{ number_format($stats['total_earned_today'], 2) }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-up">Today</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-card-lbl">Completed</div>
        <div class="stat-card-val" style="color:var(--green);" id="stat-done">{{ $stats['tasks_completed_today'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">Today</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--blue);"><i class="bi bi-list-check"></i></div>
        <div class="stat-card-lbl">Remaining</div>
        <div class="stat-card-val" style="color:var(--blue);" id="stat-remaining">{{ $stats['available_tasks'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">Available</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-card-lbl">Active Packages</div>
        <div class="stat-card-val" style="color:var(--gold);">{{ $stats['active_packages'] }}</div>
        <div class="stat-card-sub">
            @if($stats['active_packages'] == 0)
                <a href="{{ route('packages.index') }}" style="color:var(--accent);font-size:0.72rem;">Buy Package →</a>
            @else
                <span class="stat-card-badge badge-up">Running</span>
            @endif
        </div>
    </div>
</div>

{{-- ── MAIN GRID ── --}}
<div class="tk-page-grid">
    <div class="tk-list-col">
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><span class="pulse"></span> Task Queue</span>
                <span style="font-size:0.72rem;color:var(--muted);background:var(--card2);border:1px solid var(--border);padding:3px 10px;border-radius:99px;">{{ count($tasks) }} tasks</span>
            </div>

            @forelse($tasks as $taskData)
            @php
                $isAd     = $taskData['task']->task_type === 'adsterra';
                $tid      = $taskData['task']->id;
                $upid     = $taskData['user_package_id'];
                $duration = $taskData['task']->effective_skip_delay ?? 30;
                $reward   = $taskData['reward'];
                $taskUrl  = trim($taskData['task']->task_url ?? '');
                $adViewUrl = $isAd ? route('ad.view', $tid) : '';
            @endphp

            <div class="tk-item {{ $isAd ? 'is-ad' : 'is-std' }}" id="tk-{{ $tid }}-{{ $upid }}">

                {{-- Task header --}}
                <div class="tk-head">
                    <div class="tk-ico {{ $isAd ? 'ad' : 'std' }}">
                        <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                    </div>
                    <div class="tk-info">
                        <div class="tk-title">{{ $taskData['task']->title }}</div>
                        <div class="tk-tags">
                            <span class="tk-tag t-pkg">{{ $taskData['package']->name }}</span>
                            <span class="tk-tag {{ $isAd ? 't-ad' : 't-std' }}">
                                {{ $isAd ? 'Ad · '.$duration.'s' : 'Auto' }}
                            </span>
                            <span class="tk-tag t-rem">{{ $taskData['remaining_tasks'] }} left</span>
                        </div>
                    </div>
                    <div class="tk-reward">${{ number_format($reward, 2) }}</div>
                </div>

                @if($isAd)
                {{-- ══ AD TASK: click opens modal ══ --}}
                <div class="tk-ad-body"
                     data-tid="{{ $tid }}"
                     data-upid="{{ $upid }}"
                     data-duration="{{ $duration }}"
                     data-reward="{{ $reward }}"
                     data-ad-url="{{ $adViewUrl }}">

                    {{-- Visual preview card --}}
                    <div class="tk-ad-preview-card">
                        <div class="tk-ad-preview-icon">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                        <div class="tk-ad-preview-info">
                            <div style="font-size:0.78rem;font-weight:600;color:var(--text);">{{ $taskData['task']->title }}</div>
                            <div style="font-size:0.65rem;color:var(--muted);margin-top:2px;">
                                Watch for {{ $duration }}s → Earn
                                <span style="color:var(--gold);font-weight:700;">${{ number_format($reward,2) }}</span>
                            </div>
                        </div>
                        <div class="tk-ad-play-btn">
                            <i class="bi bi-play-fill"></i>
                        </div>
                    </div>

                </div>

                @else
                {{-- ══ AUTO TASK ══ --}}
                <div class="tk-auto">
                    <button type="button" class="tk-start auto-task-btn"
                        data-task-id="{{ $tid }}"
                        data-user-package-id="{{ $upid }}"
                        data-task-url="{{ $taskUrl }}"
                        data-reward="{{ $reward }}"
                        data-required-duration="{{ $taskData['task']->required_duration ?? 30 }}">
                        <i class="bi bi-play-fill"></i> Start Task
                    </button>
                    <div class="tk-bar" id="bar-{{ $tid }}-{{ $upid }}">
                        <div class="tk-bar-fill" id="fill-{{ $tid }}-{{ $upid }}"></div>
                    </div>
                </div>
                @endif

            </div>
            @empty
            <div class="tk-empty">
                <i class="bi bi-inbox"></i>
                <div class="tk-empty-t">No Tasks Available</div>
                <p class="tk-empty-s">
                    @if($stats['active_packages'] === 0)
                        No active packages. <a href="{{ route('packages.index') }}">Buy a package</a> to start earning.
                    @else
                        All tasks completed for today. Come back tomorrow!
                    @endif
                </p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="tk-side-col">
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-bar-chart-fill"></i> Today's Stats</span>
            </div>
            <div style="padding:4px 0;">
                @foreach([
                    ['icon'=>'bi-cash-coin',       'color'=>'var(--accent)', 'label'=>'Earned Today', 'id'=>'sb-earned',    'val'=>'$'.number_format($stats['total_earned_today'],2)],
                    ['icon'=>'bi-check-circle-fill','color'=>'var(--green)', 'label'=>'Tasks Done',   'id'=>'sb-done',      'val'=>$stats['tasks_completed_today']],
                    ['icon'=>'bi-list-check',       'color'=>'var(--blue)',  'label'=>'Remaining',    'id'=>'sb-remaining', 'val'=>$stats['available_tasks']],
                    ['icon'=>'bi-box-seam-fill',    'color'=>'var(--gold)',  'label'=>'Packages',     'id'=>'',             'val'=>$stats['active_packages']],
                ] as $r)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:11px 18px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:30px;height:30px;border-radius:8px;background:rgba(0,0,0,0.2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:{{ $r['color'] }};font-size:0.85rem;">
                            <i class="bi {{ $r['icon'] }}"></i>
                        </div>
                        <span style="font-size:0.78rem;color:var(--muted);">{{ $r['label'] }}</span>
                    </div>
                    <span style="font-family:'Syne',sans-serif;font-size:0.9rem;font-weight:800;color:{{ $r['color'] }}" {{ $r['id'] ? 'id='.$r['id'] : '' }}>{{ $r['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <a href="{{ route('tasks.history') }}" class="cy-hbtn outline" style="width:100%;justify-content:center;">
            <i class="bi bi-clock-history"></i> Task History
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════════
     AD MODAL — Full ad viewing experience
════════════════════════════════════════════ --}}
<div id="adModal"
     style="display:none;position:fixed;inset:0;z-index:99999;
            background:rgba(0,0,0,0.95);
            align-items:center;justify-content:center;padding:12px;">

    <div class="ad-modal-box">

        {{-- ── TOP: Header ── --}}
        <div class="adm-header">
            <div style="display:flex;align-items:center;gap:8px;">
                <div class="ad-pulse-dot"></div>
                <span style="font-size:0.78rem;font-weight:600;color:var(--muted);">Watching Ad</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <span id="adm-reward-badge"
                      style="font-family:'Syne',sans-serif;font-size:0.82rem;font-weight:800;
                             color:var(--gold);background:rgba(245,158,11,0.12);
                             border:1px solid rgba(245,158,11,0.3);
                             padding:3px 12px;border-radius:99px;"></span>
            </div>
        </div>

        {{-- ── MIDDLE: Ad iframe ── --}}
        <div class="adm-ad-wrap" id="adm-ad-wrap">

            {{-- Spinner shown while ad loads --}}
            <div id="adm-spinner">
                <div class="adm-spin-ring"></div>
                <span>Loading ad...</span>
            </div>

            {{-- Actual ad iframe — loads /ad-view/{id} from our own domain --}}
            <iframe id="adm-frame"
                    src="about:blank"
                    scrolling="no"
                    allowfullscreen
                    style="width:100%;height:220px;border:none;display:block;opacity:0;transition:opacity 0.4s;">
            </iframe>
        </div>

        {{-- ── BOTTOM: Timer + Close ── --}}
        <div class="adm-footer">

            {{-- Timer row --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">

                <div>
                    <div style="font-size:0.7rem;color:var(--muted);margin-bottom:3px;">
                        <i class="bi bi-eye-fill" style="margin-right:3px;color:var(--accent);"></i>
                        Watch the complete ad to claim reward
                    </div>
                    <div style="font-size:0.8rem;color:var(--text);">
                        Close button in
                        <span id="adm-sec"
                              style="font-family:'Syne',sans-serif;font-weight:900;
                                     color:var(--accent);font-size:1rem;margin:0 2px;"></span>s
                    </div>
                </div>

                {{-- Circular countdown ring --}}
                <div style="position:relative;width:54px;height:54px;flex-shrink:0;">
                    <svg width="54" height="54" viewBox="0 0 54 54"
                         style="transform:rotate(-90deg);position:absolute;top:0;left:0;">
                        <circle cx="27" cy="27" r="22"
                                fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="5"/>
                        <circle cx="27" cy="27" r="22"
                                fill="none" stroke="var(--accent)" stroke-width="5"
                                id="adm-ring"
                                stroke-dasharray="138.2"
                                stroke-dashoffset="138.2"
                                stroke-linecap="round"
                                style="transition:stroke-dashoffset 1s linear;"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                        <span id="adm-num"
                              style="font-family:'Syne',sans-serif;font-size:1rem;
                                     font-weight:900;color:var(--accent);">0</span>
                    </div>
                </div>
            </div>

            {{-- Progress bar --}}
            <div style="height:5px;background:rgba(255,255,255,0.06);border-radius:99px;overflow:hidden;margin-bottom:14px;">
                <div id="adm-bar"
                     style="height:100%;width:0%;border-radius:99px;
                            background:linear-gradient(90deg,#ef4444,var(--gold),var(--accent));
                            transition:width 1s linear;">
                </div>
            </div>

            {{-- Close / Claim button --}}
            <button id="adm-close-btn" disabled
                style="width:100%;padding:14px;border-radius:12px;border:none;
                       font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:700;
                       background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.3);
                       cursor:not-allowed;transition:all 0.4s;
                       display:flex;align-items:center;justify-content:center;gap:8px;">
                <i class="bi bi-hourglass-split" id="adm-close-icon"></i>
                <span id="adm-close-txt">
                    Wait <span id="adm-close-sec"></span>s
                </span>
            </button>

        </div>
    </div>
</div>

{{-- ── SUCCESS OVERLAY ── --}}
<div id="sucOv"
     style="display:none;position:fixed;inset:0;z-index:99999;
            background:rgba(0,0,0,0.9);
            align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;
                width:100%;max-width:340px;padding:40px 24px;text-align:center;">
        <div style="width:70px;height:70px;border-radius:50%;background:rgba(167,139,250,0.15);
                    display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
            <i class="bi bi-check-circle-fill" style="font-size:2.5rem;color:var(--accent);"></i>
        </div>
        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;margin-bottom:4px;">
            Task Completed!
        </div>
        <p style="color:var(--muted);font-size:0.82rem;margin-bottom:16px;">Reward added to your wallet</p>
        <div style="font-family:'Syne',sans-serif;font-size:3rem;font-weight:900;
                    color:var(--accent);line-height:1;margin-bottom:4px;">
            $<span id="earnedAmount">0.00</span>
        </div>
        <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.12em;
                    color:var(--muted);margin-bottom:24px;">USDT · Added to wallet</div>
        <button onclick="window.location.reload()"
            style="width:100%;padding:14px;border-radius:12px;
                   background:var(--accent);color:#000;border:none;cursor:pointer;
                   font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:700;">
            <i class="bi bi-arrow-clockwise"></i> Continue Tasks
        </button>
    </div>
</div>

@endsection

@push('scripts')
<style>
/* ── Layout ── */
.tk-page-grid{display:grid;grid-template-columns:1fr 260px;gap:20px;align-items:start;}
.tk-side-col{display:flex;flex-direction:column;gap:14px;}
@media(max-width:991px){
    .tk-page-grid{grid-template-columns:1fr;}
    .tk-list-col{order:0;}.tk-side-col{order:1;}
}

/* ── Task list ── */
.tk-item{border-bottom:1px solid var(--border);}
.tk-item:last-child{border-bottom:none;}
.tk-item.task-done{opacity:0.28;pointer-events:none;}
.tk-item.is-ad{border-left:3px solid rgba(167,139,250,0.6);}
.tk-item.is-std{border-left:3px solid rgba(0,245,212,0.4);}

.tk-head{display:flex;align-items:center;gap:11px;padding:12px 14px 8px;}
.tk-ico{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.88rem;flex-shrink:0;border:1px solid;}
.tk-ico.ad{background:rgba(167,139,250,0.1);color:var(--accent);border-color:rgba(167,139,250,0.25);}
.tk-ico.std{background:rgba(0,0,0,0.2);color:var(--accent);border-color:rgba(255,255,255,0.1);}
.tk-info{flex:1;min-width:0;}
.tk-title{font-family:'Syne',sans-serif;font-size:0.84rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px;}
.tk-tags{display:flex;gap:4px;flex-wrap:wrap;}
.tk-tag{font-size:0.58rem;font-weight:600;padding:1px 7px;border-radius:99px;}
.t-pkg{background:rgba(59,130,246,0.1);color:var(--blue);border:1px solid rgba(59,130,246,0.2);}
.t-ad{background:rgba(167,139,250,0.1);color:var(--accent);border:1px solid rgba(167,139,250,0.2);}
.t-std{background:rgba(34,197,94,0.08);color:var(--green);border:1px solid rgba(34,197,94,0.2);}
.t-rem{background:rgba(0,0,0,0.2);color:var(--muted);border:1px solid var(--border2);}
.tk-reward{font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--gold);flex-shrink:0;}

/* ── Ad body click area ── */
.tk-ad-body{
    position:relative;margin:0 14px 14px;border-radius:12px;overflow:hidden;
    border:1px solid var(--border);background:var(--card2);
    cursor:pointer;user-select:none;-webkit-user-select:none;
    transition:border-color 0.2s,transform 0.15s;
}
.tk-ad-body:hover{border-color:rgba(167,139,250,0.5);transform:scale(1.01);}
.tk-ad-body:active{transform:scale(0.99);}
.tk-ad-body *{pointer-events:none!important;}

/* Preview card inside ad body */
.tk-ad-preview-card{
    display:flex;align-items:center;gap:12px;
    padding:14px 14px 38px;
}
.tk-ad-preview-icon{
    width:42px;height:42px;flex-shrink:0;border-radius:10px;
    background:rgba(167,139,250,0.12);border:1px solid rgba(167,139,250,0.2);
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;color:var(--accent);
}
.tk-ad-preview-info{flex:1;min-width:0;}
.tk-ad-play-btn{
    width:36px;height:36px;border-radius:50%;flex-shrink:0;
    background:var(--accent);display:flex;align-items:center;justify-content:center;
    color:#000;font-size:0.9rem;
    box-shadow:0 0 12px rgba(167,139,250,0.4);
}

/* Tap to earn label at bottom of ad card */
.tk-ad-body::after{
    content:'▶  Tap to watch & earn';
    position:absolute;bottom:0;left:0;right:0;
    background:linear-gradient(0deg,rgba(0,0,0,0.92) 0%,transparent 100%);
    color:rgba(255,255,255,0.7);font-size:0.7rem;font-weight:600;
    padding:18px 14px 10px;
    display:flex;align-items:center;
}

/* ── Auto task ── */
.tk-auto{display:flex;align-items:center;gap:10px;padding:0 14px 14px 60px;}
.tk-start{display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;background:var(--accent);color:#000;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:700;white-space:nowrap;}
.tk-start:disabled{opacity:0.4;cursor:not-allowed;}
.tk-bar{flex:1;height:4px;background:rgba(0,0,0,0.3);border-radius:99px;overflow:hidden;display:none;}
.tk-bar.on{display:block;}
.tk-bar-fill{height:100%;width:0%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px;transition:width 0.4s;}

.tk-empty{text-align:center;padding:40px 20px;color:var(--muted);}
.tk-empty i{font-size:2.2rem;display:block;margin-bottom:10px;opacity:0.2;}
.tk-empty-t{font-family:'Syne',sans-serif;font-size:0.92rem;font-weight:700;margin-bottom:4px;color:var(--text);}
.tk-empty-s{font-size:0.8rem;}
.tk-empty-s a{color:var(--accent);}

/* ── AD MODAL ── */
.ad-modal-box{
    background:var(--card);
    border:1px solid rgba(167,139,250,0.2);
    border-radius:20px;
    width:100%;max-width:430px;
    overflow:hidden;
    box-shadow:0 0 60px rgba(167,139,250,0.12);
}
.adm-header{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 16px;
    border-bottom:1px solid var(--border);
    background:var(--card2);
}
.adm-ad-wrap{
    position:relative;
    background:#0a0a14;
    min-height:180px;
    display:flex;align-items:center;justify-content:center;
}
.adm-footer{padding:14px 16px 16px;}

/* Spinner */
#adm-spinner{
    position:absolute;inset:0;
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    gap:10px;color:rgba(255,255,255,0.25);font-size:0.72rem;z-index:2;
    background:#0a0a14;
    transition:opacity 0.4s;
}
.adm-spin-ring{
    width:30px;height:30px;border-radius:50%;
    border:2px solid rgba(255,255,255,0.08);
    border-top-color:var(--accent);
    animation:spin 0.75s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg);}}

/* Close button ready state */
#adm-close-btn.ready{
    background:var(--accent) !important;
    color:#000 !important;
    cursor:pointer !important;
    box-shadow:0 0 20px rgba(167,139,250,0.35);
}

/* Pulse dot */
.ad-pulse-dot{
    width:8px;height:8px;border-radius:50%;
    background:var(--accent);
    box-shadow:0 0 6px var(--accent);
    animation:adpulse 1.4s ease-in-out infinite;
    flex-shrink:0;
}
@keyframes adpulse{
    0%,100%{opacity:1;transform:scale(1);}
    50%{opacity:0.35;transform:scale(0.8);}
}
</style>

<script>
var _adTimer = null;
var _currentTid = null;
var _currentUpid = null;

// ════════════════════════════════════════
//  openAdModal — click এ call হয়
// ════════════════════════════════════════
function openAdModal(tid, upid, duration, reward, adUrl) {

    _currentTid  = tid;
    _currentUpid = upid;

    // Clear old timer
    if (_adTimer) { clearInterval(_adTimer); _adTimer = null; }

    // ── Load ad in iframe (our own domain route) ──
    var frame   = document.getElementById('adm-frame');
    var spinner = document.getElementById('adm-spinner');

    spinner.style.opacity = '1';
    spinner.style.pointerEvents = 'auto';
    frame.style.opacity = '0';
    frame.src = 'about:blank';

    // Small delay then load
    setTimeout(function() {
        frame.src = adUrl;
    }, 150);

    frame.onload = function() {
        if (frame.src && frame.src !== 'about:blank') {
            setTimeout(function() {
                spinner.style.opacity = '0';
                spinner.style.pointerEvents = 'none';
                frame.style.opacity = '1';
            }, 700);
        }
    };

    // ── Reward badge ──
    document.getElementById('adm-reward-badge').textContent = '+$' + parseFloat(reward).toFixed(2);

    // ── Reset progress ──
    var bar  = document.getElementById('adm-bar');
    var ring = document.getElementById('adm-ring');
    bar.style.transition  = 'none'; bar.style.width  = '0%';
    ring.style.transition = 'none'; ring.style.strokeDashoffset = '138.2';
    bar.offsetWidth; // force reflow

    // ── Reset timer display ──
    document.getElementById('adm-num').textContent      = duration;
    document.getElementById('adm-sec').textContent      = duration;
    document.getElementById('adm-close-sec').textContent = duration;

    // ── Reset close button ──
    var btn = document.getElementById('adm-close-btn');
    btn.disabled = true;
    btn.classList.remove('ready');
    btn.style.background = 'rgba(255,255,255,0.06)';
    btn.style.color = 'rgba(255,255,255,0.3)';
    btn.style.cursor = 'not-allowed';
    document.getElementById('adm-close-icon').className = 'bi bi-hourglass-split';
    document.getElementById('adm-close-txt').innerHTML =
        'Wait <span id="adm-close-sec">' + duration + '</span>s';

    // ── Show modal ──
    document.getElementById('adModal').style.display = 'flex';

    // ── Animate bar + ring after short delay ──
    setTimeout(function() {
        bar.style.transition  = 'width '  + duration + 's linear';
        bar.style.width = '100%';
        ring.style.transition = 'stroke-dashoffset ' + duration + 's linear';
        ring.style.strokeDashoffset = '0';
    }, 100);

    // ── Countdown ticker ──
    var elapsed = 0;
    _adTimer = setInterval(function() {
        elapsed++;
        var rem = Math.max(0, duration - elapsed);

        document.getElementById('adm-num').textContent = rem;
        document.getElementById('adm-sec').textContent = rem;
        var s = document.getElementById('adm-close-sec');
        if (s) s.textContent = rem;

        if (elapsed >= duration) {
            clearInterval(_adTimer);
            _adTimer = null;

            // ── Enable close button ──
            btn.disabled = false;
            btn.classList.add('ready');
            document.getElementById('adm-close-icon').className = 'bi bi-check-circle-fill';
            document.getElementById('adm-close-txt').innerHTML = 'Close &amp; Claim $' + parseFloat(reward).toFixed(2);

            // Auto submit to server
            submitTask(tid, upid, duration, reward);
        }
    }, 1000);
}

// ════════════════════════════════════════
//  Close button
// ════════════════════════════════════════
document.getElementById('adm-close-btn').addEventListener('click', function() {
    if (this.disabled) return;
    closeAdModal();
});

function closeAdModal() {
    document.getElementById('adModal').style.display = 'none';
    document.getElementById('adm-frame').src = 'about:blank';
    if (_adTimer) { clearInterval(_adTimer); _adTimer = null; }
}

// ════════════════════════════════════════
//  Submit task → credit wallet
// ════════════════════════════════════════
function submitTask(tid, upid, duration, reward) {
    fetch('/tasks/auto-verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            user_package_id: parseInt(upid),
            task_id:         parseInt(tid),
            duration:        parseInt(duration)
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {

            // ── Mark task as done visually ──
            var card = document.getElementById('tk-' + tid + '-' + upid);
            if (card) card.classList.add('task-done');

            // ── Update earned stats in page ──
            ['sb-earned','stat-earned'].forEach(function(id) {
                var el = document.getElementById(id);
                if (!el) return;
                var current = parseFloat(el.textContent.replace(/[$,]/g,'')) || 0;
                el.textContent = '$' + (current + parseFloat(reward)).toFixed(2);
            });
            ['sb-done','stat-done'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.textContent = (parseInt(el.textContent) || 0) + 1;
            });
            ['sb-remaining','stat-remaining'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.textContent = Math.max((parseInt(el.textContent) || 0) - 1, 0);
            });

            // ── Show success overlay ──
            setTimeout(function() {
                closeAdModal();
                document.getElementById('earnedAmount').textContent =
                    data.reward || parseFloat(reward).toFixed(2);
                document.getElementById('sucOv').style.display = 'flex';
            }, 500);

        } else {
            // Task failed — still allow close
            alert('Error: ' + (data.message || 'Task submission failed'));
        }
    })
    .catch(function() {
        alert('Network error. Please try again.');
    });
}

// ════════════════════════════════════════
//  Event listeners
// ════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {

    // ── Ad task: tap opens modal ──
    document.querySelectorAll('.tk-ad-body').forEach(function(div) {
        div.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            openAdModal(
                this.dataset.tid,
                this.dataset.upid,
                parseInt(this.dataset.duration),
                parseFloat(this.dataset.reward),
                this.dataset.adUrl
            );
        });
    });

    // ── Auto task: open URL + timer ──
    document.querySelectorAll('.auto-task-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tid    = this.dataset.taskId;
            var upid   = this.dataset.userPackageId;
            var url    = this.dataset.taskUrl;
            var reward = this.dataset.reward;
            var dur    = parseInt(this.dataset.requiredDuration);

            if (!url) { alert('Task URL missing.'); return; }
            var tab = window.open(url, '_blank');
            if (!tab) { alert('Please allow popups for this site.'); return; }

            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Running...';

            var bar = document.getElementById('bar-' + tid + '-' + upid);
            if (bar) bar.classList.add('on');

            var elapsed = 0;
            var iv = setInterval(function() {
                elapsed++;
                var fill = document.getElementById('fill-' + tid + '-' + upid);
                if (fill) fill.style.width = Math.min((elapsed / dur) * 100, 100) + '%';
                if (elapsed >= dur) {
                    clearInterval(iv);
                    submitTask(tid, upid, dur, reward);
                }
            }, 1000);
        });
    });

});
</script>
@endpush
