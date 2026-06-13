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
                <span style="font-size:0.72rem;color:var(--muted);background:var(--card2);border:1px solid var(--border);padding:3px 10px;border-radius:99px;">
                    {{ count($tasks) }} tasks
                </span>
            </div>

            @forelse($tasks as $taskData)
            @php
                $isAd     = $taskData['task']->task_type === 'adsterra';
                $tid      = $taskData['task']->id;
                $upid     = $taskData['user_package_id'];
                $duration = $taskData['task']->effective_skip_delay ?? 30;
                $reward   = $taskData['reward'];

                // Parse ad code
                $adCode = $taskData['task']->adsterra_ad_code ?? '';
                preg_match_all('/src=["\']([^"\']+)["\']/', $adCode, $srcM);
                $adSrcs = $srcM[1] ?? [];
                preg_match_all('/<script(?![^>]*src)[^>]*>(.*?)<\/script>/s', $adCode, $inlM);
                $adInlines = array_filter($inlM[1] ?? [], fn($s) => trim($s));
                preg_match_all('/<div[^>]*>.*?<\/div>/s', $adCode, $divM);
                $adDivs = $divM[0] ?? [];
                preg_match_all('/https?:\/\/[^\s<>"\']+/', strip_tags($adCode), $urlM);
                $adUrls = array_values(array_filter($urlM[0] ?? [], fn($u) => !str_ends_with($u, '.js') && !str_contains($u, 'googleapis')));
                $hasDiv       = !empty($adDivs);
                $hasSmartlink = !empty($adUrls);
            @endphp

            <div class="tk-item {{ $isAd ? 'type-ad' : 'type-std' }}" id="task-card-{{ $tid }}-{{ $upid }}">

                {{-- Icon --}}
                <div class="tk-item-ico {{ $isAd ? 'ad' : 'std' }}">
                    <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                </div>

                {{-- Info --}}
                <div class="tk-item-info">
                    <div class="tk-item-title">{{ $taskData['task']->title }}</div>
                    <div class="tk-item-tags">
                        <span class="tk-tag pkg"><i class="bi bi-box-seam"></i> {{ $taskData['package']->name }}</span>
                        @if($isAd)
                        <span class="tk-tag ad"><i class="bi bi-megaphone-fill"></i> Ad Task</span>
                        @else
                        <span class="tk-tag auto"><i class="bi bi-lightning-charge-fill"></i> Auto</span>
                        @endif
                        <span class="tk-tag remain"><i class="bi bi-layers"></i> {{ $taskData['remaining_tasks'] }} left</span>
                    </div>
                    <p class="tk-item-desc">{{ Str::limit($taskData['task']->description, 100) }}</p>
                    <div class="tk-item-meta">
                        @if($taskData['task']->estimated_time)
                        <span class="tk-meta-pill"><i class="bi bi-clock"></i> {{ $taskData['task']->estimated_time }} mins</span>
                        @endif
                        @if($isAd)
                        <span class="tk-meta-pill"><i class="bi bi-eye"></i> Watch {{ $duration }}s then claim</span>
                        @else
                        <span class="tk-meta-pill"><i class="bi bi-hourglass-split"></i> Stay {{ $taskData['task']->required_duration ?? 30 }}s</span>
                        @endif
                    </div>

                    {{-- Progress bar (under info, full width) --}}
                    @if($isAd)
                    <div class="tk-ad-progress-wrap" id="prog-wrap-{{ $tid }}-{{ $upid }}" style="display:none;margin-top:10px;">
                        <div class="tk-ad-progress-bar" id="prog-bar-{{ $tid }}-{{ $upid }}"></div>
                        <span class="tk-ad-prog-txt" id="prog-txt-{{ $tid }}-{{ $upid }}">0s</span>
                    </div>
                    @else
                    <div class="tk-timer" id="timer-box-{{ $tid }}-{{ $upid }}" style="margin-top:10px;">
                        <div class="tk-timer-bar">
                            <div class="tk-timer-fill" id="progress-{{ $tid }}-{{ $upid }}"></div>
                        </div>
                        <span class="tk-timer-txt" id="timer-text-{{ $tid }}-{{ $upid }}">Waiting...</span>
                    </div>
                    @endif
                </div>

                {{-- Action (right side) --}}
                <div class="tk-item-action">

                    @if($isAd)
                    {{-- ── AD ZONE — shown directly in place of button ── --}}
                    <div class="tk-ad-zone"
                         id="ad-zone-{{ $tid }}-{{ $upid }}"
                         data-task-id="{{ $tid }}"
                         data-pkg-id="{{ $upid }}"
                         data-duration="{{ $duration }}"
                         data-reward="{{ $reward }}">

                        {{-- State 1: Click to start (default) --}}
                        <div class="tk-ad-idle" id="ad-idle-{{ $tid }}-{{ $upid }}">
                            @if($hasSmartlink)
                            <a href="{{ $adUrls[0] }}" target="_blank"
                               class="tk-ad-btn"
                               onclick="startAdTimer('{{ $tid }}','{{ $upid }}',{{ $duration }},{{ $reward }})">
                                <i class="bi bi-megaphone-fill"></i>
                                <span class="tk-ad-btn-main">Watch & Earn</span>
                                <span class="tk-ad-btn-sub">click · {{ $duration }}s</span>
                            </a>
                            @elseif($hasDiv)
                            <div class="tk-ad-banner-zone"
                                 onclick="startAdTimer('{{ $tid }}','{{ $upid }}',{{ $duration }},{{ $reward }})">
                                @foreach($adDivs as $div) {!! $div !!} @endforeach
                                <div class="tk-ad-banner-overlay">Click to start</div>
                            </div>
                            @else
                            <button type="button" class="tk-ad-btn"
                                onclick="startAdTimer('{{ $tid }}','{{ $upid }}',{{ $duration }},{{ $reward }})">
                                <i class="bi bi-megaphone-fill"></i>
                                <span class="tk-ad-btn-main">Watch & Earn</span>
                                <span class="tk-ad-btn-sub">click · {{ $duration }}s</span>
                            </button>
                            @endif

                            <div class="tk-reward-num" style="margin-top:6px;">${{ number_format($reward, 2) }}</div>
                            <div class="tk-reward-sub">Per Task</div>
                        </div>

                        {{-- State 2: Timer running --}}
                        <div class="tk-ad-counting" id="ad-counting-{{ $tid }}-{{ $upid }}" style="display:none;">
                            <div class="tk-ad-countdown" id="ad-cd-{{ $tid }}-{{ $upid }}">{{ $duration }}</div>
                            <div class="tk-ad-cd-label">seconds left</div>
                        </div>

                        {{-- State 3: Claim --}}
                        <div class="tk-ad-done" id="ad-done-{{ $tid }}-{{ $upid }}" style="display:none;">
                            <button type="button" class="tk-claim-btn"
                                onclick="claimTask('{{ $tid }}','{{ $upid }}',{{ $reward }})">
                                <i class="bi bi-check-circle-fill"></i>
                                Claim ${{ number_format($reward, 2) }}
                            </button>
                        </div>

                    </div>

                    {{-- Inject ad scripts --}}
                    @foreach($adInlines as $inline)
                        @if(trim($inline))<script>{{ $inline }}</script>@endif
                    @endforeach
                    @foreach($adSrcs as $src)
                        <script src="{{ $src }}"></script>
                    @endforeach

                    @else
                    {{-- Non-ad task --}}
                    <div>
                        <div class="tk-reward-num">${{ number_format($reward, 2) }}</div>
                        <div class="tk-reward-sub">Per Task</div>
                    </div>
                    <button type="button" class="tk-start-btn std auto-task-btn"
                        data-task-id="{{ $tid }}"
                        data-user-package-id="{{ $upid }}"
                        data-task-url="{{ $taskData['task']->task_url }}"
                        data-reward="{{ $reward }}"
                        data-required-duration="{{ $taskData['task']->required_duration ?? 30 }}">
                        <i class="bi bi-play-circle-fill"></i>
                        <span class="tk-btn-label">
                            <span class="tk-btn-main">Start Task</span>
                            <span class="tk-btn-hint">auto in {{ $taskData['task']->required_duration ?? 30 }}s</span>
                        </span>
                    </button>
                    @endif

                </div>
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
                ] as $row)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(0,0,0,0.2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:{{ $row['color'] }};font-size:0.9rem;">
                            <i class="bi {{ $row['icon'] }}"></i>
                        </div>
                        <span style="font-size:0.78rem;color:var(--muted);">{{ $row['label'] }}</span>
                    </div>
                    <span style="font-family:'Syne',sans-serif;font-size:0.92rem;font-weight:800;color:{{ $row['color'] }}" {{ $row['id'] ? 'id='.$row['id'] : '' }}>{{ $row['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-info-circle-fill"></i> How It Works</span>
            </div>
            <div style="padding:14px 20px;display:flex;flex-direction:column;gap:10px;">
                @foreach([
                    ['icon'=>'bi-hand-index-fill','text'=>'Click the Watch & Earn button'],
                    ['icon'=>'bi-hourglass-split','text'=>'Wait for the countdown to finish'],
                    ['icon'=>'bi-check-circle-fill','text'=>'Claim button appears — click it'],
                    ['icon'=>'bi-wallet2','text'=>'Reward instantly added to wallet'],
                ] as $tip)
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:26px;height:26px;border-radius:7px;background:rgba(0,0,0,0.2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:0.78rem;flex-shrink:0;margin-top:1px;">
                        <i class="bi {{ $tip['icon'] }}"></i>
                    </div>
                    <span style="font-size:0.78rem;color:var(--muted);line-height:1.55;">{{ $tip['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <a href="{{ route('tasks.history') }}" class="cy-hbtn outline" style="width:100%;justify-content:center;">
            <i class="bi bi-clock-history"></i> View Task History
        </a>
    </div>
</div>

{{-- Success Modal --}}
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center;padding:40px 24px;">
                <i class="bi bi-check-circle-fill" style="font-size:3.5rem;color:var(--accent);display:block;margin-bottom:14px;"></i>
                <div style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;margin-bottom:6px;">Task Completed!</div>
                <p style="color:var(--muted);font-size:0.85rem;margin-bottom:16px;">You've earned:</p>
                <div style="font-family:'Syne',sans-serif;font-size:2.8rem;font-weight:800;color:var(--accent);line-height:1;margin-bottom:4px;">$<span id="earnedAmount">0.00</span></div>
                <div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:24px;">Added to wallet</div>
                <button type="button" class="cy-hbtn primary" style="width:100%;justify-content:center;" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Continue Tasks
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
.tk-page-grid { display:grid; grid-template-columns:1fr 280px; gap:20px; align-items:start; }
.tk-side-col  { display:flex; flex-direction:column; gap:16px; }
@media(max-width:991px) { .tk-page-grid{grid-template-columns:1fr;} .tk-list-col{order:0;} .tk-side-col{order:1;} }

.tk-item { display:grid; grid-template-columns:48px 1fr auto; gap:16px; align-items:start; padding:18px 20px; border-bottom:1px solid var(--border); transition:background 0.2s; border-left:3px solid transparent; }
.tk-item:last-child { border-bottom:none; }
.tk-item:hover { background:rgba(255,255,255,0.02); }
.tk-item.type-ad  { border-left-color:var(--blue); }
.tk-item.type-std { border-left-color:rgba(0,245,212,0.3); }
.tk-item.task-done { opacity:0.3; pointer-events:none; }
.tk-item-ico { width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;border:1px solid; }
.tk-item-ico.ad  { background:rgba(59,130,246,0.1);color:var(--blue);border-color:rgba(59,130,246,0.2); }
.tk-item-ico.std { background:rgba(0,0,0,0.2);color:var(--accent);border-color:rgba(255,255,255,0.1); }
.tk-item-title { font-family:'Syne',sans-serif;font-size:0.88rem;font-weight:700;margin-bottom:8px; }
.tk-item-tags  { display:flex;gap:5px;flex-wrap:wrap;margin-bottom:8px; }
.tk-tag { display:inline-flex;align-items:center;gap:4px;font-size:0.62rem;font-weight:600;padding:2px 8px;border-radius:99px;border:1px solid; }
.tk-tag.pkg    { color:var(--blue);  border-color:rgba(59,130,246,0.25);background:rgba(59,130,246,0.08); }
.tk-tag.ad     { color:var(--accent);border-color:rgba(0,245,212,0.25); background:rgba(0,245,212,0.06); }
.tk-tag.auto   { color:var(--green); border-color:rgba(34,197,94,0.25); background:rgba(34,197,94,0.06); }
.tk-tag.remain { color:var(--muted); border-color:var(--border2);        background:rgba(0,0,0,0.2); }
.tk-item-desc  { font-size:0.82rem;color:var(--muted);line-height:1.6;margin-bottom:8px; }
.tk-item-meta  { display:flex;gap:14px;flex-wrap:wrap; }
.tk-meta-pill  { display:flex;align-items:center;gap:4px;font-size:0.68rem;color:var(--muted); }
.tk-meta-pill i { color:var(--accent); }
.tk-item-action { display:flex;flex-direction:column;align-items:flex-end;gap:6px;min-width:140px; }
.tk-reward-num  { font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);line-height:1;text-align:right; }
.tk-reward-sub  { font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);text-align:right; }

/* Auto task */
.tk-start-btn { display:flex;align-items:center;justify-content:center;gap:7px;padding:10px 16px;border:none;cursor:pointer;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:0.82rem;font-weight:700;width:100%;transition:all 0.25s; }
.tk-start-btn:hover { transform:translateY(-2px); }
.tk-start-btn:disabled { opacity:0.4;cursor:not-allowed;transform:none; }
.tk-start-btn.std { background:var(--accent);color:#000; }
.tk-btn-label { display:flex;flex-direction:column;align-items:center;gap:2px; }
.tk-btn-main  { font-size:0.78rem;font-weight:700; }
.tk-btn-hint  { font-size:0.65rem;opacity:0.75;font-weight:400; }
.tk-timer { background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;display:none;width:100%; }
.tk-timer.on { display:block; }
.tk-timer-bar { background:rgba(0,0,0,0.3);border-radius:99px;height:3px;margin-bottom:5px;overflow:hidden; }
.tk-timer-fill { height:100%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px; }
.tk-timer-txt { font-size:0.65rem;color:var(--muted);text-align:center;display:block; }

/* ── AD ZONE (right side, replaces button) ── */
.tk-ad-zone { width: 100%; display:flex; flex-direction:column; align-items:stretch; gap:6px; }
.tk-ad-idle  { display:flex; flex-direction:column; align-items:flex-end; gap:4px; }

/* The clickable ad button */
.tk-ad-btn {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px; padding: 12px 16px; border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff; border: none; cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: all 0.2s; text-decoration: none;
    width: 100%; text-align: center;
    font-size: 0.82rem;
}
.tk-ad-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59,130,246,0.4); color: #fff; }
.tk-ad-btn i { font-size: 1.1rem; }
.tk-ad-btn-main { font-size: 0.82rem; font-weight: 700; line-height: 1; }
.tk-ad-btn-sub  { font-size: 0.62rem; opacity: 0.75; }

/* Banner zone */
.tk-ad-banner-zone {
    position: relative; cursor: pointer;
    border-radius: 10px; overflow: hidden;
    width: 100%; text-align: center;
    min-height: 60px; background: var(--card2);
    border: 1px solid var(--border);
}
.tk-ad-banner-zone iframe { max-width: 100% !important; pointer-events: none; }
.tk-ad-banner-overlay {
    position: absolute; inset: 0;
    background: rgba(59,130,246,0.7);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.78rem; font-weight: 700; color: #fff;
    border-radius: 10px; transition: opacity 0.3s;
}

/* Timer running state */
.tk-ad-counting {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 2px;
    padding: 14px; border-radius: 10px;
    background: rgba(59,130,246,0.1);
    border: 1px solid rgba(59,130,246,0.3);
    width: 100%;
}
.tk-ad-countdown {
    font-family: 'Syne', sans-serif;
    font-size: 2rem; font-weight: 800;
    color: var(--accent); line-height: 1;
    animation: countPulse 1s infinite;
}
@keyframes countPulse { 0%,100%{opacity:1} 50%{opacity:0.6} }
.tk-ad-cd-label { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); }

/* Progress bar under info */
.tk-ad-progress-wrap {
    height: 4px; background: rgba(0,0,0,0.3);
    border-radius: 99px; overflow: hidden;
    position: relative;
}
.tk-ad-progress-bar {
    height: 100%; width: 0%;
    background: linear-gradient(90deg, var(--accent2), var(--accent));
    border-radius: 99px; transition: width 1s linear;
}
.tk-ad-prog-txt {
    font-size: 0.6rem; color: var(--muted);
    margin-top: 3px; display: block; text-align: right;
}

/* Claim button */
.tk-claim-btn {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 12px; border-radius: 9px;
    background: var(--accent); color: #000;
    border: none; cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 0.85rem; font-weight: 700;
    transition: opacity 0.2s;
    animation: claimPop 0.4s ease;
}
@keyframes claimPop { from{transform:scale(0.85);opacity:0} to{transform:scale(1);opacity:1} }
.tk-claim-btn:hover { opacity: 0.9; }
.tk-claim-btn:disabled { opacity: 0.5; cursor:not-allowed; }

.tk-empty { text-align:center;padding:48px 20px;color:var(--muted); }
.tk-empty i { font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.2; }
.tk-empty-t { font-family:'Syne',sans-serif;font-size:0.95rem;font-weight:700;margin-bottom:6px;color:var(--text); }
.tk-empty-s { font-size:0.82rem; }
.tk-empty-s a { color:var(--accent); }

@media(max-width:600px) {
    .tk-item { grid-template-columns:40px 1fr; gap:10px; padding:14px; }
    .tk-item-action { grid-column:1/-1; }
    .tk-ad-zone, .tk-item-action { width:100%; align-items:stretch; }
    .tk-reward-num { font-size:1.1rem; }
    .tk-item-ico   { width:36px; height:36px; font-size:0.9rem; }
    .tk-item-title { font-size:0.82rem; }
    .tk-item-desc  { font-size:0.75rem; }
    .tk-tag        { font-size:0.58rem; }
}
</style>

<script>
// Active timers tracker
const adTimers = {};

function startAdTimer(taskId, pkgId, duration, reward) {
    const key = `${taskId}-${pkgId}`;
    if (adTimers[key]) return; // already running

    const idle     = document.getElementById(`ad-idle-${key}`);
    const counting = document.getElementById(`ad-counting-${key}`);
    const cdEl     = document.getElementById(`ad-cd-${key}`);
    const progWrap = document.getElementById(`prog-wrap-${key}`);
    const progBar  = document.getElementById(`prog-bar-${key}`);
    const progTxt  = document.getElementById(`prog-txt-${key}`);

    // Show counting state
    if (idle)     idle.style.display     = 'none';
    if (counting) counting.style.display = 'flex';
    if (progWrap) progWrap.style.display = 'block';

    let elapsed = 0;
    adTimers[key] = setInterval(() => {
        elapsed++;
        const rem = Math.max(duration - elapsed, 0);
        const pct = Math.min((elapsed / duration) * 100, 100);
        if (cdEl)    cdEl.textContent      = rem;
        if (progBar) progBar.style.width   = pct + '%';
        if (progTxt) progTxt.textContent   = elapsed + 's / ' + duration + 's';

        if (elapsed >= duration) {
            clearInterval(adTimers[key]);
            delete adTimers[key];

            // Show claim
            if (counting) counting.style.display = 'none';
            const done = document.getElementById(`ad-done-${key}`);
            if (done) done.style.display = 'flex';
            if (cdEl) cdEl.textContent = '✓';
            if (progTxt) progTxt.textContent = 'Done!';
        }
    }, 1000);
}

function claimTask(taskId, pkgId, reward) {
    const key     = `${taskId}-${pkgId}`;
    const claimBtn= document.querySelector(`#ad-done-${key} .tk-claim-btn`);
    if (claimBtn) { claimBtn.disabled = true; claimBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...'; }

    // Get elapsed from progress text
    const progTxt = document.getElementById(`prog-txt-${key}`);
    let elapsed = 0;
    if (progTxt) {
        const match = progTxt.textContent.match(/^(\d+)/);
        if (match) elapsed = parseInt(match[1]);
    }

    fetch('/tasks/auto-verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            user_package_id: parseInt(pkgId),
            task_id: parseInt(taskId),
            duration: elapsed
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const card = document.getElementById(`task-card-${key}`);
            if (card) card.classList.add('task-done');
            ['sb-earned','stat-earned'].forEach(id => { const el=document.getElementById(id); if(el){const c=parseFloat(el.textContent.replace(/[$,]/g,''))||0; el.textContent='$'+(c+parseFloat(reward)).toFixed(2);} });
            ['sb-done','stat-done'].forEach(id => { const el=document.getElementById(id); if(el) el.textContent=(parseInt(el.textContent)||0)+1; });
            ['sb-remaining','stat-remaining'].forEach(id => { const el=document.getElementById(id); if(el) el.textContent=Math.max((parseInt(el.textContent)||0)-1,0); });
            document.getElementById('earnedAmount').textContent = data.reward || parseFloat(reward).toFixed(2);
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            alert('Failed: ' + (data.message || 'Unknown error'));
            if (claimBtn) { claimBtn.disabled=false; claimBtn.innerHTML='<i class="bi bi-check-circle-fill"></i> Claim $'+parseFloat(reward).toFixed(2); }
        }
    })
    .catch(e => { alert('Network error: '+e.message); });
}

document.addEventListener('DOMContentLoaded', function () {
    document.body.appendChild(document.getElementById('successModal'));

    // Auto task
    document.querySelectorAll('.auto-task-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const taskId=this.dataset.taskId, pkgId=this.dataset.userPackageId;
            const url=this.dataset.taskUrl, reward=this.dataset.reward;
            const duration=parseInt(this.dataset.requiredDuration);
            if (!url) { alert('Task URL missing.'); return; }
            const tab=window.open(url,'_blank');
            if (!tab) { alert('Popup blocked!'); return; }
            this.disabled=true;
            this.querySelector('.tk-btn-main').textContent='Processing...';
            this.querySelector('.tk-btn-hint').textContent='Please wait...';
            const timerBox=document.getElementById(`timer-box-${taskId}-${pkgId}`);
            if (timerBox) timerBox.classList.add('on');
            let elapsed=0;
            const iv=setInterval(()=>{
                elapsed++;
                const bar=document.getElementById(`progress-${taskId}-${pkgId}`);
                const txt=document.getElementById(`timer-text-${taskId}-${pkgId}`);
                if (bar) bar.style.width=Math.min((elapsed/duration)*100,100)+'%';
                if (txt) txt.textContent=Math.max(duration-elapsed,0)+'s remaining...';
                if (elapsed>=duration) {
                    clearInterval(iv);
                    fetch('/tasks/auto-verify',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({user_package_id:parseInt(pkgId),task_id:parseInt(taskId),duration:elapsed})})
                    .then(r=>r.json()).then(data=>{
                        if(data.success){
                            document.getElementById(`task-card-${taskId}-${pkgId}`)?.classList.add('task-done');
                            ['sb-earned','stat-earned'].forEach(id=>{const el=document.getElementById(id);if(el){const c=parseFloat(el.textContent.replace(/[$,]/g,''))||0;el.textContent='$'+(c+parseFloat(reward)).toFixed(2);}});
                            ['sb-done','stat-done'].forEach(id=>{const el=document.getElementById(id);if(el)el.textContent=(parseInt(el.textContent)||0)+1;});
                            ['sb-remaining','stat-remaining'].forEach(id=>{const el=document.getElementById(id);if(el)el.textContent=Math.max((parseInt(el.textContent)||0)-1,0);});
                            document.getElementById('earnedAmount').textContent=data.reward||parseFloat(reward).toFixed(2);
                            new bootstrap.Modal(document.getElementById('successModal')).show();
                        } else { alert('Failed: '+data.message); }
                    });
                }
            },1000);
        });
    });
});
</script>
@endpush
