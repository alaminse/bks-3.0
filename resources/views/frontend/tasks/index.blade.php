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
                $adCode   = $isAd ? ($taskData['task']->adsterra_ad_code ?? '') : '';

                // Parse ad code
                preg_match_all('/<script[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $adCode, $sm);
                $adSrcs = $sm[1] ?? [];
                preg_match_all('/<script(?![^>]*src)[^>]*>(.*?)<\/script>/si', $adCode, $im);
                $adInlines = array_filter($im[1] ?? [], fn($s) => trim($s));
                preg_match_all('/<div[^>]*>.*?<\/div>/si', $adCode, $dm);
                $adDivs = $dm[0] ?? [];

                // Find smartlink — plain URL on its own line
                $lines = explode("\n", $adCode);
                $plainUrls = [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (preg_match('/^https?:\/\/\S+$/', $line) && !str_ends_with($line, '.js')) {
                        $plainUrls[] = $line;
                    }
                }

                // Extract atOptions key to build ad URL
                $adKey = '';
                if (preg_match("/['\"]key['\"]\s*:\s*['\"]([a-f0-9]+)['\"]/", $adCode, $km)) {
                    $adKey = $km[1];
                }

                $hasDiv = !empty($adDivs);

                // Build adLink: task_url → plain smartlink URL → first script src domain
                $taskUrl = trim($taskData['task']->task_url ?? '');
                if (!empty($taskUrl)) {
                    $adLink = $taskUrl;
                } elseif (!empty($plainUrls)) {
                    $adLink = $plainUrls[0];
                } elseif (!empty($adSrcs)) {
                    // Extract domain from first script src
                    $parsed = parse_url($adSrcs[0]);
                    $adLink = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '');
                } else {
                    $adLink = '';
                }
            @endphp

            <div class="tk-item {{ $isAd ? 'is-ad' : 'is-std' }}" id="tk-{{ $tid }}-{{ $upid }}">

                {{-- Header row --}}
                <div class="tk-head">
                    <div class="tk-ico {{ $isAd ? 'ad' : 'std' }}">
                        <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                    </div>
                    <div class="tk-info">
                        <div class="tk-title">{{ $taskData['task']->title }}</div>
                        <div class="tk-tags">
                            <span class="tk-tag t-pkg">{{ $taskData['package']->name }}</span>
                            <span class="tk-tag {{ $isAd ? 't-ad' : 't-std' }}">{{ $isAd ? 'Ad·'.$duration.'s' : 'Auto' }}</span>
                            <span class="tk-tag t-rem">{{ $taskData['remaining_tasks'] }} left</span>
                        </div>
                    </div>
                    <div class="tk-reward">${{ number_format($reward, 2) }}</div>
                </div>

                @if($isAd)
                {{-- AD BODY --}}
                <div class="tk-ad-body"
                     id="adb-{{ $tid }}-{{ $upid }}"
                     data-tid="{{ $tid }}"
                     data-upid="{{ $upid }}"
                     data-duration="{{ $duration }}"
                     data-reward="{{ $reward }}"
                     data-link="{{ $adLink }}">

                    {{-- Ad content — pointer-events:none so clicks bubble up --}}
                    <div class="tk-ad-inner" style="pointer-events:none;">
                        @if($hasDiv)
                            @foreach($adDivs as $div){!! $div !!}@endforeach
                        @else
                            <div class="tk-ad-ph"><i class="bi bi-megaphone-fill"></i></div>
                        @endif
                    </div>

                    {{-- Inject ad scripts --}}
                    @foreach($adInlines as $il)
                        @if(trim($il))<script>{!! $il !!}</script>@endif
                    @endforeach
                    @foreach($adSrcs as $src)
                        <script src="{{ $src }}"></script>
                    @endforeach
                </div>

                @else
                {{-- AUTO TASK --}}
                <div class="tk-auto">
                    <button type="button" class="tk-start auto-task-btn"
                        data-task-id="{{ $tid }}"
                        data-user-package-id="{{ $upid }}"
                        data-task-url="{{ $taskData['task']->task_url }}"
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

{{-- ══ AD OVERLAY ══ --}}
<div id="adOv" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.88);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;width:100%;max-width:460px;overflow:hidden;">
        <div id="adOv-ad" style="width:100%;background:#0a0a14;min-height:80px;display:flex;align-items:center;justify-content:center;overflow:hidden;"></div>
        <div style="padding:16px 20px 20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <span style="font-size:0.78rem;color:var(--muted);">Watching ad...</span>
                <span style="font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:800;color:var(--accent);" id="adOv-num">0</span>
            </div>
            <div style="height:6px;background:var(--card2);border-radius:99px;overflow:hidden;">
                <div id="adOv-bar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ SUCCESS OVERLAY ══ --}}
<div id="sucOv" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.88);align-items:center;justify-content:center;padding:16px;">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:16px;width:100%;max-width:380px;padding:40px 24px;text-align:center;">
        <i class="bi bi-check-circle-fill" style="font-size:3.5rem;color:var(--accent);display:block;margin-bottom:14px;"></i>
        <div style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;margin-bottom:6px;">Task Completed!</div>
        <p style="color:var(--muted);font-size:0.85rem;margin-bottom:16px;">You've earned:</p>
        <div style="font-family:'Syne',sans-serif;font-size:2.8rem;font-weight:800;color:var(--accent);line-height:1;margin-bottom:4px;">$<span id="earnedAmount">0.00</span></div>
        <div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:24px;">Added to wallet</div>
        <button onclick="window.location.reload()" style="width:100%;padding:13px;border-radius:10px;background:var(--accent);color:#000;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:700;">
            <i class="bi bi-arrow-clockwise"></i> Continue Tasks
        </button>
    </div>
</div>

@endsection

@push('scripts')
<style>
.tk-page-grid { display:grid; grid-template-columns:1fr 260px; gap:20px; align-items:start; }
.tk-side-col  { display:flex; flex-direction:column; gap:14px; }
@media(max-width:991px){ .tk-page-grid{grid-template-columns:1fr;} .tk-list-col{order:0;} .tk-side-col{order:1;} }

.tk-item { border-bottom:1px solid var(--border); }
.tk-item:last-child { border-bottom:none; }
.tk-item.task-done  { opacity:0.3; pointer-events:none; }
.tk-item.is-ad  { border-left:3px solid rgba(59,130,246,0.7); }
.tk-item.is-std { border-left:3px solid rgba(0,245,212,0.4); }

.tk-head { display:flex; align-items:center; gap:11px; padding:12px 14px 8px; }
.tk-ico  { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.88rem; flex-shrink:0; border:1px solid; }
.tk-ico.ad  { background:rgba(59,130,246,0.1); color:var(--blue);   border-color:rgba(59,130,246,0.25); }
.tk-ico.std { background:rgba(0,0,0,0.2);       color:var(--accent); border-color:rgba(255,255,255,0.1); }
.tk-info  { flex:1; min-width:0; }
.tk-title { font-family:'Syne',sans-serif; font-size:0.84rem; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:4px; }
.tk-tags  { display:flex; gap:4px; flex-wrap:wrap; }
.tk-tag   { font-size:0.58rem; font-weight:600; padding:1px 7px; border-radius:99px; }
.t-pkg { background:rgba(59,130,246,0.1); color:var(--blue);   border:1px solid rgba(59,130,246,0.2); }
.t-ad  { background:rgba(0,245,212,0.08); color:var(--accent); border:1px solid rgba(0,245,212,0.2); }
.t-std { background:rgba(34,197,94,0.08); color:var(--green);  border:1px solid rgba(34,197,94,0.2); }
.t-rem { background:rgba(0,0,0,0.2);      color:var(--muted);  border:1px solid var(--border2); }
.tk-reward { font-family:'Syne',sans-serif; font-size:1.1rem; font-weight:800; color:var(--gold); flex-shrink:0; }

/* AD BODY */
.tk-ad-body {
    position: relative;
    margin: 0 14px 12px;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--border);
    background: var(--card2);
    min-height: 60px;
    cursor: pointer;
    /* Block ALL child pointer events — clicks always bubble to this div */
    -webkit-user-select: none;
    user-select: none;
}
.tk-ad-body * {
    pointer-events: none !important;
}
.tk-ad-inner {
    width: 100%;
    max-height: 150px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tk-ad-inner iframe { max-width:100% !important; width:100% !important; max-height:150px !important; border:none; display:block; }
.tk-ad-inner > div  { max-width:100% !important; }
.tk-ad-ph { height:70px; display:flex; align-items:center; justify-content:center; color:var(--blue); font-size:2rem; opacity:0.4; }

/* AUTO TASK */
.tk-auto { display:flex; align-items:center; gap:10px; padding:0 14px 12px 60px; }
.tk-start { display:inline-flex; align-items:center; gap:5px; padding:8px 16px; border-radius:8px; background:var(--accent); color:#000; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; font-size:0.8rem; font-weight:700; white-space:nowrap; }
.tk-start:disabled { opacity:0.4; cursor:not-allowed; }
.tk-bar  { flex:1; height:4px; background:rgba(0,0,0,0.3); border-radius:99px; overflow:hidden; display:none; }
.tk-bar.on { display:block; }
.tk-bar-fill { height:100%; width:0%; background:linear-gradient(90deg,var(--accent2),var(--accent)); border-radius:99px; transition:width 0.4s; }

.tk-empty { text-align:center; padding:40px 20px; color:var(--muted); }
.tk-empty i { font-size:2.2rem; display:block; margin-bottom:10px; opacity:0.2; }
.tk-empty-t { font-family:'Syne',sans-serif; font-size:0.92rem; font-weight:700; margin-bottom:4px; color:var(--text); }
.tk-empty-s { font-size:0.8rem; }
.tk-empty-s a { color:var(--accent); }
</style>

<script>
var _iv = null;

function showOverlay(id) {
    document.getElementById(id).style.display = 'flex';
}
function hideOverlay(id) {
    document.getElementById(id).style.display = 'none';
}

function startAd(tid, upid, duration, reward) {

    // Clear any previous timer
    if (_iv) { clearInterval(_iv); _iv = null; }

    // Put ad clone in overlay
    var adArea = document.getElementById('adOv-ad');
    adArea.innerHTML = '';
    var inner = document.querySelector('#tk-' + tid + '-' + upid + ' .tk-ad-inner');
    if (inner) {
        var clone = inner.cloneNode(true);
        clone.style.pointerEvents = 'none';
        clone.style.maxHeight = '200px';
        adArea.appendChild(clone);
    }

    // Reset bar
    var bar = document.getElementById('adOv-bar');
    bar.style.transition = 'none';
    bar.style.width = '0%';
    bar.offsetWidth; // force reflow
    bar.style.transition = 'width ' + duration + 's linear';
    bar.style.width = '100%';

    // Countdown
    document.getElementById('adOv-num').textContent = duration;
    showOverlay('adOv');

    var elapsed = 0;
    _iv = setInterval(function() {
        elapsed++;
        var rem = duration - elapsed;
        document.getElementById('adOv-num').textContent = rem < 0 ? 0 : rem;
        if (elapsed >= duration) {
            clearInterval(_iv);
            _iv = null;
            hideOverlay('adOv');
            submitTask(tid, upid, duration, reward);
        }
    }, 1000);
}

function submitTask(tid, upid, duration, reward) {
    fetch('/tasks/auto-verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            user_package_id: parseInt(upid),
            task_id: parseInt(tid),
            duration: parseInt(duration)
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var card = document.getElementById('tk-' + tid + '-' + upid);
            if (card) card.classList.add('task-done');
            ['sb-earned','stat-earned'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) { var c = parseFloat(el.textContent.replace(/[$,]/g,'')) || 0; el.textContent = '$' + (c + parseFloat(reward)).toFixed(2); }
            });
            ['sb-done','stat-done'].forEach(function(id) { var el=document.getElementById(id); if(el) el.textContent=(parseInt(el.textContent)||0)+1; });
            ['sb-remaining','stat-remaining'].forEach(function(id) { var el=document.getElementById(id); if(el) el.textContent=Math.max((parseInt(el.textContent)||0)-1,0); });
            document.getElementById('earnedAmount').textContent = data.reward || parseFloat(reward).toFixed(2);
            showOverlay('sucOv');
        } else {
            alert('Failed: ' + (data.message || 'Error'));
        }
    })
    .catch(function(e) { alert('Network error: ' + e.message); });
}

document.addEventListener('DOMContentLoaded', function() {

    // Ad click — listen on the wrapper div
    document.querySelectorAll('.tk-ad-body').forEach(function(div) {
        div.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var tid      = this.dataset.tid;
            var upid     = this.dataset.upid;
            var duration = parseInt(this.dataset.duration);
            var reward   = parseFloat(this.dataset.reward);
            var link     = this.dataset.link || '';

            // Open new tab immediately inside click handler
            if (link && link.length > 5) {
                window.open(link, '_blank');
            }

            // Start timer overlay
            startAd(tid, upid, duration, reward);
        });
    });

    // Auto task buttons
    document.querySelectorAll('.auto-task-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tid  = this.dataset.taskId;
            var upid = this.dataset.userPackageId;
            var url  = this.dataset.taskUrl;
            var reward  = this.dataset.reward;
            var dur  = parseInt(this.dataset.requiredDuration);
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
                if (fill) fill.style.width = Math.min((elapsed/dur)*100, 100) + '%';
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
