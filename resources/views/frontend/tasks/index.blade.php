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
                $adCode   = $isAd ? trim($taskData['task']->adsterra_ad_code ?? '') : '';
                $taskUrl  = trim($taskData['task']->task_url ?? '');

                // ── Detect ad type ──
                $isSmartlink  = preg_match('/^https?:\/\/\S+$/m', $adCode) && !str_contains($adCode, '<script') && !str_contains($adCode, '<div');
                $isPopunder   = str_contains($adCode, 'effectivecpmnetwork.com/1b') || str_contains($adCode, 'effectivecpmnetwork.com/e0');
                $isNativeBanner = str_contains($adCode, 'effectivecpmnetwork.com/ab');
                $isBanner     = str_contains($adCode, 'highperformanceformat.com');

                // ── Build srcdoc HTML for iframe ──
                // Wrap ad code in a self-contained HTML page
                // This avoids atOptions variable clash and X-Frame-Options issues
                $srcdocHtml = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>html,body{margin:0;padding:0;background:transparent;overflow:hidden;display:flex;align-items:center;justify-content:center;min-height:100%;}</style></head><body>' . $adCode . '</body></html>';

                // Escape for HTML attribute
                $srcdocEscaped = htmlspecialchars($srcdocHtml, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                // ── Smartlink: extract URL ──
                $smartlinkUrl = '';
                if ($isSmartlink) {
                    preg_match('/https?:\/\/\S+/m', $adCode, $slm);
                    $smartlinkUrl = trim($slm[0] ?? '');
                }
                if (empty($smartlinkUrl) && !empty($taskUrl)) {
                    $smartlinkUrl = $taskUrl;
                }

                // ── Ad label ──
                if ($isSmartlink)    $adTypeLabel = 'Smartlink';
                elseif ($isPopunder) $adTypeLabel = 'Social Ad';
                elseif ($isNativeBanner) $adTypeLabel = 'Native Ad';
                elseif ($isBanner)   $adTypeLabel = 'Banner';
                else                 $adTypeLabel = 'Ad';

                // ── For display preview: determine banner size ──
                $bannerH = 100; // default preview height
                if (str_contains($adCode, "'height' : 250") || str_contains($adCode, '"height" : 250')) $bannerH = 250;
                elseif (str_contains($adCode, "'height' : 90"))  $bannerH = 90;
                elseif (str_contains($adCode, "'height' : 60"))  $bannerH = 60;
                elseif (str_contains($adCode, "'height' : 50"))  $bannerH = 50;
                elseif (str_contains($adCode, "'height' : 600")) $bannerH = 200; // cap at 200
                elseif (str_contains($adCode, "'height' : 300")) $bannerH = 200;
            @endphp

            <div class="tk-item {{ $isAd ? 'is-ad' : 'is-std' }}" id="tk-{{ $tid }}-{{ $upid }}">

                <div class="tk-head">
                    <div class="tk-ico {{ $isAd ? 'ad' : 'std' }}">
                        <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                    </div>
                    <div class="tk-info">
                        <div class="tk-title">{{ $taskData['task']->title }}</div>
                        <div class="tk-tags">
                            <span class="tk-tag t-pkg">{{ $taskData['package']->name }}</span>
                            <span class="tk-tag {{ $isAd ? 't-ad' : 't-std' }}">
                                {{ $isAd ? $adTypeLabel.'·'.$duration.'s' : 'Auto' }}
                            </span>
                            <span class="tk-tag t-rem">{{ $taskData['remaining_tasks'] }} left</span>
                        </div>
                    </div>
                    <div class="tk-reward">${{ number_format($reward, 2) }}</div>
                </div>

                @if($isAd)
                {{-- ══ AD TASK ══ --}}
                <div class="tk-ad-body"
                     data-tid="{{ $tid }}"
                     data-upid="{{ $upid }}"
                     data-duration="{{ $duration }}"
                     data-reward="{{ $reward }}"
                     data-srcdoc="{{ $srcdocEscaped }}"
                     data-smartlink="{{ $smartlinkUrl }}"
                     data-banner-h="{{ $bannerH }}"
                     data-is-popunder="{{ $isPopunder ? '1' : '0' }}"
                     data-is-smartlink="{{ $isSmartlink ? '1' : '0' }}">

                    {{-- Preview thumbnail --}}
                    <div class="tk-ad-preview">
                        @if($isSmartlink)
                            <i class="bi bi-link-45deg" style="font-size:2rem;color:var(--accent);opacity:0.5;"></i>
                            <span style="font-size:0.7rem;color:var(--muted);margin-top:4px">Smartlink Ad</span>
                        @elseif($isPopunder)
                            <i class="bi bi-window-stack" style="font-size:2rem;color:var(--blue);opacity:0.5;"></i>
                            <span style="font-size:0.7rem;color:var(--muted);margin-top:4px">Social Bar Ad</span>
                        @elseif($isNativeBanner)
                            <i class="bi bi-card-image" style="font-size:2rem;color:var(--green);opacity:0.5;"></i>
                            <span style="font-size:0.7rem;color:var(--muted);margin-top:4px">Native Banner</span>
                        @else
                            <i class="bi bi-megaphone-fill" style="font-size:2rem;color:var(--blue);opacity:0.5;"></i>
                            <span style="font-size:0.7rem;color:var(--muted);margin-top:4px">Banner Ad</span>
                        @endif
                    </div>

                    {{-- Tap overlay --}}
                    <div class="tk-ad-click-label">
                        <i class="bi bi-hand-index-thumb-fill"></i>
                        Tap to watch &amp; earn ${{ number_format($reward, 2) }}
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
                    ['icon'=>'bi-cash-coin','color'=>'var(--accent)','label'=>'Earned Today','id'=>'sb-earned','val'=>'$'.number_format($stats['total_earned_today'],2)],
                    ['icon'=>'bi-check-circle-fill','color'=>'var(--green)','label'=>'Tasks Done','id'=>'sb-done','val'=>$stats['tasks_completed_today']],
                    ['icon'=>'bi-list-check','color'=>'var(--blue)','label'=>'Remaining','id'=>'sb-remaining','val'=>$stats['available_tasks']],
                    ['icon'=>'bi-box-seam-fill','color'=>'var(--gold)','label'=>'Packages','id'=>'','val'=>$stats['active_packages']],
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

{{-- ══════════════════════════════════════
     AD MODAL
══════════════════════════════════════ --}}
<div id="adModal" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.93);align-items:center;justify-content:center;padding:12px;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:20px;width:100%;max-width:420px;overflow:hidden;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--card2);">
            <div style="display:flex;align-items:center;gap:8px;">
                <div class="ad-pulse-dot"></div>
                <span style="font-size:0.78rem;font-weight:600;color:var(--muted)">Watching Ad</span>
            </div>
            <span id="adModal-reward" style="font-family:'Syne',sans-serif;font-size:0.82rem;font-weight:800;color:var(--gold);background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.25);padding:3px 10px;border-radius:99px;"></span>
        </div>

        {{-- ── AD IFRAME (srcdoc) — no X-Frame-Options issue ── --}}
        <div style="width:100%;background:#0a0a14;position:relative;overflow:hidden;" id="adModal-wrap">
            <iframe id="adModal-frame"
                    srcdoc=""
                    sandbox="allow-scripts allow-same-origin allow-popups allow-forms"
                    scrolling="no"
                    style="width:100%;height:160px;border:none;display:block;"
                    loading="lazy">
            </iframe>
            {{-- Loading placeholder --}}
            <div id="adModal-loading" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:#0a0a14;color:rgba(255,255,255,0.2);gap:8px;">
                <i class="bi bi-megaphone-fill" style="font-size:2rem;"></i>
                <span style="font-size:0.7rem;">Loading ad...</span>
            </div>
        </div>

        {{-- Progress + Close --}}
        <div style="padding:14px 16px 16px;">

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <div>
                    <div style="font-size:0.7rem;color:var(--muted);margin-bottom:2px;">Please watch the ad</div>
                    <div style="font-size:0.75rem;color:var(--muted);">
                        Close in
                        <span id="adModal-sec" style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent);font-size:0.95rem;margin:0 2px;">0</span>s
                    </div>
                </div>
                {{-- Ring countdown --}}
                <div style="position:relative;width:50px;height:50px;flex-shrink:0;">
                    <svg width="50" height="50" viewBox="0 0 50 50" style="transform:rotate(-90deg)">
                        <circle cx="25" cy="25" r="20" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="4"/>
                        <circle cx="25" cy="25" r="20" fill="none" stroke="var(--accent)" stroke-width="4"
                            id="adModal-ring"
                            stroke-dasharray="125.6"
                            stroke-dashoffset="125.6"
                            stroke-linecap="round"
                            style="transition:stroke-dashoffset 1s linear"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                        <span id="adModal-num" style="font-family:'Syne',sans-serif;font-size:0.95rem;font-weight:900;color:var(--accent);">0</span>
                    </div>
                </div>
            </div>

            {{-- Progress bar --}}
            <div style="height:4px;background:rgba(255,255,255,0.06);border-radius:99px;overflow:hidden;margin-bottom:12px;">
                <div id="adModal-bar" style="height:100%;width:0%;background:linear-gradient(90deg,#ef4444,var(--gold),var(--accent));border-radius:99px;transition:width 1s linear;"></div>
            </div>

            {{-- Close button --}}
            <button id="adModal-close" disabled
                style="width:100%;padding:12px;border-radius:10px;border:1.5px solid rgba(255,255,255,0.08);cursor:not-allowed;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:700;background:rgba(255,255,255,0.04);color:var(--muted);transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:7px;">
                <i class="bi bi-hourglass-split" id="adModal-close-icon"></i>
                <span id="adModal-close-txt">Wait <span id="adModal-close-sec">0</span>s</span>
            </button>
        </div>
    </div>
</div>

{{-- SUCCESS OVERLAY --}}
<div id="sucOv" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.88);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;width:100%;max-width:360px;padding:36px 24px;text-align:center;">
        <i class="bi bi-check-circle-fill" style="font-size:3.5rem;color:var(--accent);display:block;margin-bottom:14px;"></i>
        <div style="font-family:'Syne',sans-serif;font-size:1.15rem;font-weight:800;margin-bottom:6px;">Task Completed!</div>
        <p style="color:var(--muted);font-size:0.82rem;margin-bottom:14px;">You earned:</p>
        <div style="font-family:'Syne',sans-serif;font-size:2.6rem;font-weight:800;color:var(--accent);line-height:1;margin-bottom:4px;">$<span id="earnedAmount">0.00</span></div>
        <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:22px;">Added to wallet</div>
        <button onclick="window.location.reload()" style="width:100%;padding:13px;border-radius:10px;background:var(--accent);color:#000;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:0.88rem;font-weight:700;">
            <i class="bi bi-arrow-clockwise"></i> Continue Tasks
        </button>
    </div>
</div>

@endsection

@push('scripts')
<style>
.tk-page-grid{display:grid;grid-template-columns:1fr 260px;gap:20px;align-items:start;}
.tk-side-col{display:flex;flex-direction:column;gap:14px;}
@media(max-width:991px){.tk-page-grid{grid-template-columns:1fr;}.tk-list-col{order:0;}.tk-side-col{order:1;}}

.tk-item{border-bottom:1px solid var(--border);}
.tk-item:last-child{border-bottom:none;}
.tk-item.task-done{opacity:0.3;pointer-events:none;}
.tk-item.is-ad{border-left:3px solid rgba(59,130,246,0.7);}
.tk-item.is-std{border-left:3px solid rgba(0,245,212,0.4);}

.tk-head{display:flex;align-items:center;gap:11px;padding:12px 14px 8px;}
.tk-ico{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.88rem;flex-shrink:0;border:1px solid;}
.tk-ico.ad{background:rgba(59,130,246,0.1);color:var(--blue);border-color:rgba(59,130,246,0.25);}
.tk-ico.std{background:rgba(0,0,0,0.2);color:var(--accent);border-color:rgba(255,255,255,0.1);}
.tk-info{flex:1;min-width:0;}
.tk-title{font-family:'Syne',sans-serif;font-size:0.84rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px;}
.tk-tags{display:flex;gap:4px;flex-wrap:wrap;}
.tk-tag{font-size:0.58rem;font-weight:600;padding:1px 7px;border-radius:99px;}
.t-pkg{background:rgba(59,130,246,0.1);color:var(--blue);border:1px solid rgba(59,130,246,0.2);}
.t-ad{background:rgba(0,245,212,0.08);color:var(--accent);border:1px solid rgba(0,245,212,0.2);}
.t-std{background:rgba(34,197,94,0.08);color:var(--green);border:1px solid rgba(34,197,94,0.2);}
.t-rem{background:rgba(0,0,0,0.2);color:var(--muted);border:1px solid var(--border2);}
.tk-reward{font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--gold);flex-shrink:0;}

/* AD BODY */
.tk-ad-body{
    position:relative;margin:0 14px 12px;border-radius:10px;overflow:hidden;
    border:1px solid var(--border);background:var(--card2);
    cursor:pointer;user-select:none;-webkit-user-select:none;
}
.tk-ad-body *{pointer-events:none!important;}
.tk-ad-preview{
    height:80px;display:flex;flex-direction:column;
    align-items:center;justify-content:center;gap:2px;
}
.tk-ad-click-label{
    position:absolute;bottom:0;left:0;right:0;
    background:linear-gradient(0deg,rgba(0,0,0,0.92) 0%,transparent 100%);
    color:#fff;font-size:0.72rem;font-weight:600;
    padding:22px 12px 10px;display:flex;align-items:center;gap:5px;
    pointer-events:none!important;
}
.tk-ad-click-label i{color:var(--gold);}

/* AUTO */
.tk-auto{display:flex;align-items:center;gap:10px;padding:0 14px 12px 60px;}
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

/* Close button enabled */
#adModal-close.ready{
    background:var(--accent)!important;color:#000!important;
    border-color:var(--accent)!important;cursor:pointer!important;
}

/* Pulse dot */
.ad-pulse-dot{width:9px;height:9px;border-radius:50%;background:var(--accent);box-shadow:0 0 6px var(--accent);animation:adpulse 1.4s ease-in-out infinite;}
@keyframes adpulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:0.4;transform:scale(0.82);}}
</style>

<script>
var _adTimer = null;

// ─────────────────────────────────────────
//  OPEN AD MODAL
// ─────────────────────────────────────────
function openAdModal(tid, upid, duration, reward, srcdoc, bannerH) {

    if (_adTimer) { clearInterval(_adTimer); _adTimer = null; }

    // ── Set iframe srcdoc — this loads ad inside sandboxed iframe ──
    // srcdoc avoids X-Frame-Options completely because we're not loading a URL
    var frame = document.getElementById('adModal-frame');
    var loading = document.getElementById('adModal-loading');

    // Set height based on ad size
    var h = Math.min(parseInt(bannerH) || 160, 220);
    frame.style.height = h + 'px';

    // Show loading
    loading.style.display = 'flex';

    // Set srcdoc to load ad
    frame.srcdoc = srcdoc;

    // Hide loading after ad has time to render
    frame.onload = function() {
        setTimeout(function() {
            loading.style.display = 'none';
        }, 800);
    };

    // ── Reward ──
    document.getElementById('adModal-reward').textContent = '+$' + parseFloat(reward).toFixed(2);

    // ── Reset bar + ring ──
    var bar  = document.getElementById('adModal-bar');
    var ring = document.getElementById('adModal-ring');
    bar.style.transition  = 'none'; bar.style.width = '0%';
    ring.style.transition = 'none'; ring.style.strokeDashoffset = '125.6';
    bar.offsetWidth;

    // ── Reset countdown ──
    document.getElementById('adModal-num').textContent = duration;
    document.getElementById('adModal-sec').textContent = duration;

    // ── Reset close button ──
    var closeBtn = document.getElementById('adModal-close');
    closeBtn.disabled = true;
    closeBtn.classList.remove('ready');
    document.getElementById('adModal-close-icon').className = 'bi bi-hourglass-split';
    document.getElementById('adModal-close-txt').innerHTML =
        'Wait <span id="adModal-close-sec">' + duration + '</span>s';

    // ── Show modal ──
    document.getElementById('adModal').style.display = 'flex';

    // ── Animate ──
    setTimeout(function() {
        bar.style.transition  = 'width ' + duration + 's linear';
        bar.style.width = '100%';
        ring.style.transition = 'stroke-dashoffset ' + duration + 's linear';
        ring.style.strokeDashoffset = '0';
    }, 80);

    // ── Countdown ──
    var elapsed = 0;
    _adTimer = setInterval(function() {
        elapsed++;
        var rem = Math.max(0, duration - elapsed);

        document.getElementById('adModal-num').textContent = rem;
        document.getElementById('adModal-sec').textContent = rem;
        var s = document.getElementById('adModal-close-sec');
        if (s) s.textContent = rem;

        if (elapsed >= duration) {
            clearInterval(_adTimer); _adTimer = null;

            // Enable close
            var btn = document.getElementById('adModal-close');
            btn.disabled = false;
            btn.classList.add('ready');
            document.getElementById('adModal-close-icon').className = 'bi bi-check-circle-fill';
            document.getElementById('adModal-close-txt').innerHTML = 'Close &amp; Claim Reward';

            // Submit
            submitTask(tid, upid, duration, reward);
        }
    }, 1000);
}

// ─────────────────────────────────────────
//  CLOSE MODAL
// ─────────────────────────────────────────
document.getElementById('adModal-close').addEventListener('click', function() {
    if (this.disabled) return;
    document.getElementById('adModal').style.display = 'none';
    // Clear iframe to stop ad
    document.getElementById('adModal-frame').srcdoc = '';
    if (_adTimer) { clearInterval(_adTimer); _adTimer = null; }
});

// ─────────────────────────────────────────
//  SUBMIT TASK
// ─────────────────────────────────────────
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
            var card = document.getElementById('tk-' + tid + '-' + upid);
            if (card) card.classList.add('task-done');

            ['sb-earned','stat-earned'].forEach(function(id) {
                var el = document.getElementById(id);
                if (!el) return;
                var c = parseFloat(el.textContent.replace(/[$,]/g,'')) || 0;
                el.textContent = '$' + (c + parseFloat(reward)).toFixed(2);
            });
            ['sb-done','stat-done'].forEach(function(id) {
                var el = document.getElementById(id); if (el) el.textContent = (parseInt(el.textContent)||0)+1;
            });
            ['sb-remaining','stat-remaining'].forEach(function(id) {
                var el = document.getElementById(id); if (el) el.textContent = Math.max((parseInt(el.textContent)||0)-1,0);
            });

            setTimeout(function() {
                document.getElementById('adModal').style.display = 'none';
                document.getElementById('adModal-frame').srcdoc = '';
                document.getElementById('earnedAmount').textContent = data.reward || parseFloat(reward).toFixed(2);
                document.getElementById('sucOv').style.display = 'flex';
            }, 600);

        } else {
            alert('Failed: ' + (data.message || 'Error'));
        }
    })
    .catch(function() { alert('Network error. Please try again.'); });
}

// ─────────────────────────────────────────
//  EVENT LISTENERS
// ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {

    // ── Ad task click ──
    document.querySelectorAll('.tk-ad-body').forEach(function(div) {
        div.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var tid       = this.dataset.tid;
            var upid      = this.dataset.upid;
            var duration  = parseInt(this.dataset.duration);
            var reward    = parseFloat(this.dataset.reward);
            var srcdoc    = this.dataset.srcdoc || '';
            var bannerH   = this.dataset.bannerH || '160';

            openAdModal(tid, upid, duration, reward, srcdoc, bannerH);
        });
    });

    // ── Auto task ──
    document.querySelectorAll('.auto-task-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tid    = this.dataset.taskId;
            var upid   = this.dataset.userPackageId;
            var url    = this.dataset.taskUrl;
            var reward = this.dataset.reward;
            var dur    = parseInt(this.dataset.requiredDuration);
            if (!url) { alert('Task URL missing.'); return; }
            var tab = window.open(url, '_blank');
            if (!tab) { alert('Popup blocked!'); return; }
            this.disabled = true;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Running...';
            var bar = document.getElementById('bar-' + tid + '-' + upid);
            if (bar) bar.classList.add('on');
            var elapsed = 0;
            var iv = setInterval(function() {
                elapsed++;
                var fill = document.getElementById('fill-' + tid + '-' + upid);
                if (fill) fill.style.width = Math.min((elapsed/dur)*100,100)+'%';
                if (elapsed >= dur) { clearInterval(iv); submitTask(tid,upid,dur,reward); }
            }, 1000);
        });
    });

});
</script>
@endpush
