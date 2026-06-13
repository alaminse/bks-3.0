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

                if ($isAd) {
                    $adCode = $taskData['task']->adsterra_ad_code ?? '';
                    preg_match_all('/<script[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $adCode, $sm);
                    $adSrcs = $sm[1] ?? [];
                    preg_match_all('/<script(?![^>]*src)[^>]*>(.*?)<\/script>/si', $adCode, $im);
                    $adInlines = array_filter($im[1] ?? [], fn($s) => trim($s));
                    preg_match_all('/<div[^>]*>.*?<\/div>/si', $adCode, $dm);
                    $adDivs = $dm[0] ?? [];
                    preg_match_all('/https?:\/\/[^\s<>"\']+/', strip_tags($adCode), $um);
                    $adUrls = array_values(array_filter($um[0] ?? [], fn($u) => !str_ends_with($u, '.js')));
                    $hasDiv  = !empty($adDivs);
                    $hasLink = !empty($adUrls);
                }
            @endphp

            {{-- 4-col grid for ad tasks, 3-col for normal --}}
            <div class="tk-item {{ $isAd ? 'type-ad tk-item-ad' : 'type-std' }}"
                 id="task-card-{{ $tid }}-{{ $upid }}"
                 data-duration="{{ $duration }}">

                {{-- Col 1: Icon --}}
                <div class="tk-item-ico {{ $isAd ? 'ad' : 'std' }}">
                    <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                </div>

                {{-- Col 2: Info --}}
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

                    {{-- Auto-task timer bar --}}
                    @if(!$isAd)
                    <div class="tk-timer" id="timer-box-{{ $tid }}-{{ $upid }}" style="margin-top:10px;">
                        <div class="tk-timer-bar"><div class="tk-timer-fill" id="progress-{{ $tid }}-{{ $upid }}"></div></div>
                        <span class="tk-timer-txt" id="timer-text-{{ $tid }}-{{ $upid }}">Waiting...</span>
                    </div>
                    @endif
                </div>

                {{-- Col 3: AD SLOT (only for ad tasks) --}}
                @if($isAd)
                <div class="tk-ad-slot" id="ad-slot-{{ $tid }}-{{ $upid }}">

                    {{-- ── State: IDLE — show the actual ad ── --}}
                    <div class="tk-ad-slot-idle" id="ad-idle-{{ $tid }}-{{ $upid }}">

                        @if($hasDiv)
                        {{-- Banner ad with div --}}
                        <div class="tk-ad-clickable"
                             onclick="adClicked('{{ $tid }}','{{ $upid }}',{{ $duration }},{{ $reward }})"
                             id="ad-clickable-{{ $tid }}-{{ $upid }}">
                            @foreach($adDivs as $div){!! $div !!}@endforeach
                            <div class="tk-ad-overlay" id="ad-overlay-{{ $tid }}-{{ $upid }}">
                                <i class="bi bi-hand-index-fill"></i>
                                <span>Click to start timer</span>
                            </div>
                        </div>

                        @elseif($hasLink)
                        {{-- Smartlink —  open link + start timer --}}
                        <a href="{{ $adUrls[0] }}" target="_blank"
                           class="tk-ad-smartlink"
                           onclick="adClicked('{{ $tid }}','{{ $upid }}',{{ $duration }},{{ $reward }})">
                            <i class="bi bi-megaphone-fill"></i>
                            <div>
                                <div class="tk-ad-smartlink-title">View Advertisement</div>
                                <div class="tk-ad-smartlink-sub">{{ $duration }}s · ${{ number_format($reward, 2) }}</div>
                            </div>
                            <i class="bi bi-arrow-right-circle-fill" style="margin-left:auto;font-size:1.1rem;"></i>
                        </a>

                        @else
                        {{-- Script-only — clickable placeholder --}}
                        <button class="tk-ad-smartlink"
                                onclick="adClicked('{{ $tid }}','{{ $upid }}',{{ $duration }},{{ $reward }})">
                            <i class="bi bi-megaphone-fill"></i>
                            <div>
                                <div class="tk-ad-smartlink-title">Activate Ad</div>
                                <div class="tk-ad-smartlink-sub">{{ $duration }}s · ${{ number_format($reward, 2) }}</div>
                            </div>
                            <i class="bi bi-play-fill" style="margin-left:auto;font-size:1.1rem;"></i>
                        </button>
                        @endif

                        {{-- Ad scripts injected here --}}
                        @foreach($adInlines as $il)@if(trim($il))<script>{{ $il }}</script>@endif@endforeach
                        @foreach($adSrcs as $src)<script src="{{ $src }}"></script>@endforeach
                    </div>

                    {{-- ── State: COUNTING ── --}}
                    <div class="tk-ad-slot-count" id="ad-count-{{ $tid }}-{{ $upid }}" style="display:none;">
                        <div class="tk-ad-count-num" id="ad-cd-{{ $tid }}-{{ $upid }}">{{ $duration }}</div>
                        <div class="tk-ad-count-lbl">seconds</div>
                        <div class="tk-ad-count-bar-wrap">
                            <div class="tk-ad-count-bar" id="ad-bar-{{ $tid }}-{{ $upid }}"></div>
                        </div>
                    </div>

                    {{-- ── State: DONE — Claim ── --}}
                    <div class="tk-ad-slot-done" id="ad-done-{{ $tid }}-{{ $upid }}" style="display:none;">
                        <button type="button" class="tk-claim-btn"
                                onclick="claimAd('{{ $tid }}','{{ $upid }}',{{ $reward }})">
                            <i class="bi bi-check-circle-fill"></i>
                            Claim ${{ number_format($reward, 2) }}
                        </button>
                    </div>

                </div>
                @endif

                {{-- Col 4: Action --}}
                <div class="tk-item-action">
                    @if($isAd)
                        {{-- Reward shown, no extra button needed --}}
                        <div class="tk-reward-num">${{ number_format($reward, 2) }}</div>
                        <div class="tk-reward-sub">Per Task</div>
                    @else
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
                    ['icon'=>'bi-hand-index-fill','text'=>'Click the ad in the middle column'],
                    ['icon'=>'bi-hourglass-split','text'=>'Wait for the countdown to finish'],
                    ['icon'=>'bi-check-circle-fill','text'=>'Click Claim to get your reward'],
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
/* ── PAGE GRID ── */
.tk-page-grid { display:grid; grid-template-columns:1fr 280px; gap:20px; align-items:start; }
.tk-side-col  { display:flex; flex-direction:column; gap:16px; }
@media(max-width:991px) { .tk-page-grid{grid-template-columns:1fr;} .tk-list-col{order:0;} .tk-side-col{order:1;} }

/* ── TASK ITEM ── */
/* Normal tasks: icon | info | action */
.tk-item {
    display: grid;
    grid-template-columns: 48px 1fr auto;
    gap: 16px; align-items: center;
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
    transition: background 0.2s;
    border-left: 3px solid transparent;
}
/* Ad tasks: icon | info | AD SLOT | action */
.tk-item-ad {
    grid-template-columns: 48px 1fr 180px auto;
}
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

.tk-item-action { display:flex;flex-direction:column;align-items:flex-end;gap:8px;min-width:120px; }
.tk-reward-num  { font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);line-height:1;text-align:right; }
.tk-reward-sub  { font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);text-align:right; }

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

.tk-empty { text-align:center;padding:48px 20px;color:var(--muted); }
.tk-empty i { font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.2; }
.tk-empty-t { font-family:'Syne',sans-serif;font-size:0.95rem;font-weight:700;margin-bottom:6px;color:var(--text); }
.tk-empty-s { font-size:0.82rem; }
.tk-empty-s a { color:var(--accent); }

/* ── AD SLOT (middle column) ── */
.tk-ad-slot { width:100%; min-width:0; }
.tk-ad-slot-idle,
.tk-ad-slot-count,
.tk-ad-slot-done { width:100%; }

/* Clickable banner wrapper */
.tk-ad-clickable {
    position: relative; cursor: pointer;
    border-radius: 8px; overflow: hidden;
    border: 2px solid rgba(59,130,246,0.4);
    transition: border-color 0.2s;
    min-height: 60px; background: var(--card2);
    display: flex; align-items: center; justify-content: center;
}
.tk-ad-clickable:hover { border-color: var(--accent); }
.tk-ad-clickable iframe { max-width:100% !important; pointer-events:none; }
.tk-ad-clickable > *:not(.tk-ad-overlay) { pointer-events:none; }

.tk-ad-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0.6);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 5px; color: #fff; font-size: 0.72rem; font-weight: 600;
    border-radius: 6px; transition: opacity 0.3s;
    pointer-events: none;
}
.tk-ad-overlay i { font-size: 1.2rem; color: var(--accent); }
.tk-ad-overlay.gone { opacity:0; }

/* Smartlink / placeholder button */
.tk-ad-smartlink {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px; border-radius: 9px;
    border: 2px dashed rgba(59,130,246,0.5);
    background: rgba(59,130,246,0.06);
    color: var(--text); text-decoration: none;
    cursor: pointer; transition: all 0.2s;
    width: 100%; font-family: 'DM Sans',sans-serif;
    font-size: 0.82rem;
}
.tk-ad-smartlink:hover {
    border-color: var(--accent);
    background: rgba(0,245,212,0.06);
    color: var(--text);
}
.tk-ad-smartlink i:first-child { font-size:1.3rem; color:var(--blue); flex-shrink:0; }
.tk-ad-smartlink-title { font-weight:700; font-size:0.82rem; line-height:1.2; }
.tk-ad-smartlink-sub   { font-size:0.65rem; color:var(--muted); }

/* Counting state */
.tk-ad-slot-count {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 4px; padding: 12px;
    border-radius: 9px;
    background: rgba(59,130,246,0.08);
    border: 1px solid rgba(59,130,246,0.25);
}
.tk-ad-count-num {
    font-family: 'Syne', sans-serif;
    font-size: 2rem; font-weight: 800;
    color: var(--accent); line-height: 1;
    animation: pulse 1s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
.tk-ad-count-lbl { font-size:0.6rem; text-transform:uppercase; letter-spacing:0.1em; color:var(--muted); }
.tk-ad-count-bar-wrap { width:100%; height:3px; background:rgba(0,0,0,0.3); border-radius:99px; overflow:hidden; margin-top:6px; }
.tk-ad-count-bar { height:100%; width:0%; background:linear-gradient(90deg,var(--accent2),var(--accent)); border-radius:99px; transition:width 1s linear; }

/* Done / Claim */
.tk-ad-slot-done { display:flex; }
.tk-claim-btn {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    width: 100%; padding: 12px 10px; border-radius: 9px;
    background: var(--accent); color: #000;
    border: none; cursor: pointer;
    font-family: 'DM Sans',sans-serif; font-size: 0.82rem; font-weight: 700;
    transition: opacity 0.2s;
    animation: claimIn 0.35s ease;
}
@keyframes claimIn { from{transform:scale(0.88);opacity:0} to{transform:scale(1);opacity:1} }
.tk-claim-btn:hover { opacity:0.9; }
.tk-claim-btn:disabled { opacity:0.5;cursor:not-allowed; }

/* Mobile — hide ad slot col, stack below */
@media(max-width:768px) {
    .tk-item-ad { grid-template-columns:44px 1fr auto; }
    .tk-ad-slot { grid-column:1/-1; margin-top:8px; }
    .tk-item-action { min-width:80px; }
    .tk-reward-num { font-size:1.1rem; }
}
@media(max-width:480px) {
    .tk-item, .tk-item-ad { grid-template-columns:40px 1fr; gap:10px; padding:14px; }
    .tk-item-action { grid-column:1/-1; flex-direction:row; align-items:center; gap:8px; }
    .tk-ad-slot { grid-column:1/-1; }
    .tk-item-title { font-size:0.82rem; }
    .tk-item-desc  { font-size:0.75rem; }
    .tk-tag        { font-size:0.58rem; }
}
</style>

<script>
const _adTimers = {};

function adClicked(tid, upid, duration, reward) {
    const key     = `${tid}-${upid}`;
    if (_adTimers[key]) return; // already running

    const idle    = document.getElementById(`ad-idle-${key}`);
    const count   = document.getElementById(`ad-count-${key}`);
    const cdNum   = document.getElementById(`ad-cd-${key}`);
    const bar     = document.getElementById(`ad-bar-${key}`);
    const overlay = document.getElementById(`ad-overlay-${key}`);

    // Hide overlay if banner
    if (overlay) overlay.classList.add('gone');

    // Switch to counting state
    if (idle)  idle.style.display  = 'none';
    if (count) count.style.display = 'flex';

    let elapsed = 0;
    _adTimers[key] = setInterval(() => {
        elapsed++;
        const pct = Math.min((elapsed / duration) * 100, 100);
        const rem = Math.max(duration - elapsed, 0);
        if (cdNum) cdNum.textContent  = rem;
        if (bar)   bar.style.width    = pct + '%';

        if (elapsed >= duration) {
            clearInterval(_adTimers[key]);
            delete _adTimers[key];

            // Show claim
            if (count) count.style.display = 'none';
            const done = document.getElementById(`ad-done-${key}`);
            if (done) done.style.display = 'flex';
        }
    }, 1000);
}

function claimAd(tid, upid, reward) {
    const key     = `${tid}-${upid}`;
    const card    = document.getElementById(`task-card-${key}`);
    const duration= card ? parseInt(card.dataset.duration) : 30;
    const btn     = document.querySelector(`#ad-done-${key} .tk-claim-btn`);
    if (btn) { btn.disabled=true; btn.innerHTML='<i class="bi bi-hourglass-split"></i> Processing...'; }

    fetch('/tasks/auto-verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ user_package_id:parseInt(upid), task_id:parseInt(tid), duration })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (card) card.classList.add('task-done');
            ['sb-earned','stat-earned'].forEach(id => { const el=document.getElementById(id); if(el){const c=parseFloat(el.textContent.replace(/[$,]/g,''))||0; el.textContent='$'+(c+parseFloat(reward)).toFixed(2);} });
            ['sb-done','stat-done'].forEach(id => { const el=document.getElementById(id); if(el) el.textContent=(parseInt(el.textContent)||0)+1; });
            ['sb-remaining','stat-remaining'].forEach(id => { const el=document.getElementById(id); if(el) el.textContent=Math.max((parseInt(el.textContent)||0)-1,0); });
            document.getElementById('earnedAmount').textContent = data.reward || parseFloat(reward).toFixed(2);
            new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
            alert('Failed: '+(data.message||'Error'));
            if (btn) { btn.disabled=false; btn.innerHTML='<i class="bi bi-check-circle-fill"></i> Claim $'+parseFloat(reward).toFixed(2); }
        }
    })
    .catch(e => { alert('Network error: '+e.message); });
}

document.addEventListener('DOMContentLoaded', function () {
    document.body.appendChild(document.getElementById('successModal'));

    // Auto task
    document.querySelectorAll('.auto-task-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const tid=this.dataset.taskId, upid=this.dataset.userPackageId;
            const url=this.dataset.taskUrl, reward=this.dataset.reward;
            const duration=parseInt(this.dataset.requiredDuration);
            if (!url) { alert('Task URL missing.'); return; }
            const tab=window.open(url,'_blank');
            if (!tab) { alert('Popup blocked!'); return; }
            this.disabled=true;
            this.querySelector('.tk-btn-main').textContent='Processing...';
            this.querySelector('.tk-btn-hint').textContent='Please wait...';
            const tb=document.getElementById(`timer-box-${tid}-${upid}`);
            if (tb) tb.classList.add('on');
            let elapsed=0;
            const iv=setInterval(()=>{
                elapsed++;
                const b=document.getElementById(`progress-${tid}-${upid}`);
                const t=document.getElementById(`timer-text-${tid}-${upid}`);
                if(b) b.style.width=Math.min((elapsed/duration)*100,100)+'%';
                if(t) t.textContent=Math.max(duration-elapsed,0)+'s remaining...';
                if(elapsed>=duration){
                    clearInterval(iv);
                    fetch('/tasks/auto-verify',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')},body:JSON.stringify({user_package_id:parseInt(upid),task_id:parseInt(tid),duration:elapsed})})
                    .then(r=>r.json()).then(data=>{
                        if(data.success){
                            document.getElementById(`task-card-${tid}-${upid}`)?.classList.add('task-done');
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
