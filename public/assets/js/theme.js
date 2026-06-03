// ============================================================
//  TopTrade — Theme Switcher
//  Include this file in every page before </body>
// ============================================================

const TT_THEMES = [
    { id:'cyber',   name:'Cyber',   colors:['#00f5d4','#6366f1'], desc:'Default dark teal'  },
    { id:'gold',    name:'Gold',    colors:['#f59e0b','#ef4444'], desc:'Rich golden amber'   },
    { id:'emerald', name:'Emerald', colors:['#10b981','#0ea5e9'], desc:'Deep forest green'   },
    { id:'rose',    name:'Rose',    colors:['#f43f5e','#ec4899'], desc:'Bold crimson pink'    },
    { id:'purple',  name:'Purple',  colors:['#a855f7','#6366f1'], desc:'Royal violet'        },
    { id:'ocean',   name:'Ocean',   colors:['#0ea5e9','#06b6d4'], desc:'Deep sea blue'       },
]

const TT_VARS = {
    cyber:   { black:'#050507', dark:'#0c0c12', card:'#111119', card2:'#16161f', border:'rgba(255,255,255,0.07)', border2:'rgba(255,255,255,0.12)', accent:'#00f5d4', accent2:'#6366f1', text:'#e8e8f0', muted:'#6b6b80' },
    gold:    { black:'#0c0800', dark:'#140e00', card:'#1a1200', card2:'#201800', border:'rgba(255,215,0,0.08)',   border2:'rgba(255,215,0,0.15)',   accent:'#f59e0b', accent2:'#ef4444', text:'#f0e8d0', muted:'#7a6a40' },
    emerald: { black:'#020c07', dark:'#041a0e', card:'#061f10', card2:'#082614', border:'rgba(0,255,136,0.07)',  border2:'rgba(0,255,136,0.14)',  accent:'#10b981', accent2:'#0ea5e9', text:'#d0f0e0', muted:'#4a7a60' },
    rose:    { black:'#0d0005', dark:'#1a000a', card:'#1f0008', card2:'#25000d', border:'rgba(244,63,94,0.08)',  border2:'rgba(244,63,94,0.15)',  accent:'#f43f5e', accent2:'#ec4899', text:'#f0d0d8', muted:'#7a4050' },
    purple:  { black:'#05010d', dark:'#0d0520', card:'#120828', card2:'#170b30', border:'rgba(168,85,247,0.08)', border2:'rgba(168,85,247,0.15)', accent:'#a855f7', accent2:'#6366f1', text:'#e8d8f8', muted:'#6a4a80' },
    ocean:   { black:'#00080f', dark:'#001525', card:'#001a2e', card2:'#002038', border:'rgba(14,165,233,0.08)', border2:'rgba(14,165,233,0.15)', accent:'#0ea5e9', accent2:'#06b6d4', text:'#d0e8f8', muted:'#3a6080' },
}

// Apply theme vars to :root
function ttApply(id) {
    const v = TT_VARS[id]
    if (!v) return
    const r = document.documentElement
    r.style.setProperty('--black',   v.black)
    r.style.setProperty('--dark',    v.dark)
    r.style.setProperty('--card',    v.card)
    r.style.setProperty('--card2',   v.card2)
    r.style.setProperty('--border',  v.border)
    r.style.setProperty('--border2', v.border2)
    r.style.setProperty('--accent',  v.accent)
    r.style.setProperty('--accent2', v.accent2)
    r.style.setProperty('--text',    v.text)
    r.style.setProperty('--muted',   v.muted)
    r.style.setProperty('--cyan',    v.accent)  // backward compat
    document.body.style.background = v.black
    localStorage.setItem('tt_theme', id)
    ttUpdateUI(id)
}

// Update switcher UI active state
function ttUpdateUI(id) {
    document.querySelectorAll('.tt-preset-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.theme === id)
    })
    const label = document.getElementById('tt-active-label')
    if (label) {
        const t = TT_THEMES.find(t => t.id === id)
        if (t) label.textContent = t.name
    }
}

// Build & inject switcher HTML
function ttBuild() {
    const html = `
    <style>
        .tt-fab {
            position: fixed; bottom: 90px; right: 20px; z-index: 99999;
            width: 46px; height: 46px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border: none; cursor: pointer; display: flex; align-items: center;
            justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            transition: transform 0.3s; color: #000;
        }
        .tt-fab:hover { transform: scale(1.1) rotate(15deg); }
        .tt-fab.open  { transform: rotate(90deg) scale(1.05); }
        .tt-backdrop  { display: none; position: fixed; inset: 0; z-index: 99997; }
        .tt-backdrop.open { display: block; }
        .tt-drawer {
            position: fixed; bottom: 148px; right: 16px; z-index: 99998;
            width: 260px; background: var(--card);
            border: 1px solid var(--border2); border-radius: 16px;
            padding: 18px; display: none;
            box-shadow: 0 16px 48px rgba(0,0,0,0.7);
        }
        .tt-drawer.open { display: block; }
        .tt-drawer-title {
            font-family: 'Syne', sans-serif; font-size: 0.82rem; font-weight: 700;
            color: var(--text); margin-bottom: 4px; display: flex;
            align-items: center; justify-content: space-between;
        }
        .tt-drawer-sub { font-size: 0.68rem; color: var(--muted); margin-bottom: 16px; }
        .tt-presets { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-bottom: 14px; }
        .tt-preset-btn {
            display: flex; flex-direction: column; align-items: center; gap: 6px;
            background: var(--dark); border: 1px solid var(--border);
            border-radius: 10px; padding: 10px 6px; cursor: pointer;
            transition: all 0.2s; font-family: 'DM Sans', sans-serif;
        }
        .tt-preset-btn:hover { border-color: var(--border2); }
        .tt-preset-btn.active { border-color: var(--accent); background: rgba(255,255,255,0.04); }
        .tt-swatch {
            width: 32px; height: 32px; border-radius: 8px; position: relative; overflow: hidden;
        }
        .tt-swatch-inner {
            position: absolute; inset: 0;
            display: grid; grid-template-columns: 1fr 1fr;
        }
        .tt-preset-name { font-size: 0.62rem; font-weight: 600; color: var(--muted); }
        .tt-preset-btn.active .tt-preset-name { color: var(--accent); }
        .tt-check {
            display: none; position: absolute; inset: 0;
            align-items: center; justify-content: center;
            background: rgba(0,0,0,0.4); font-size: 14px;
        }
        .tt-preset-btn.active .tt-check { display: flex; }
        .tt-divider { height: 1px; background: var(--border); margin: 12px 0; }
        .tt-custom-label { font-size: 0.7rem; font-weight: 600; color: var(--muted); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.08em; }
        .tt-custom-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .tt-custom-key { font-size: 0.75rem; color: var(--muted); }
        .tt-color-wrap { position: relative; width: 36px; height: 28px; border-radius: 7px; overflow: hidden; border: 1px solid var(--border2); cursor: pointer; }
        .tt-color-wrap input[type=color] { position: absolute; inset: -4px; width: calc(100% + 8px); height: calc(100% + 8px); opacity: 0; cursor: pointer; }
        .tt-color-preview { position: absolute; inset: 0; pointer-events: none; border-radius: 6px; }
        .tt-apply-btn {
            width: 100%; padding: 10px; border-radius: 9px; margin-top: 6px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #000; font-weight: 700; font-size: 0.78rem; border: none;
            cursor: pointer; font-family: 'DM Sans', sans-serif; transition: opacity 0.2s;
        }
        .tt-apply-btn:hover { opacity: 0.9; }
        .tt-reset-btn {
            width: 100%; padding: 8px; border-radius: 9px; margin-top: 6px;
            background: transparent; color: var(--muted); font-size: 0.72rem;
            border: 1px solid var(--border); cursor: pointer;
            font-family: 'DM Sans', sans-serif; transition: all 0.2s;
        }
        .tt-reset-btn:hover { border-color: var(--border2); color: var(--text); }
        @media(max-width:768px) {
            .tt-fab    { bottom: 82px; right: 14px; width: 42px; height: 42px; }
            .tt-drawer { bottom: 136px; right: 12px; width: calc(100vw - 24px); max-width: 280px; }
        }
    </style>

    <button class="tt-fab" id="ttFab" onclick="ttToggle()" title="Change Theme">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/>
            <circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/>
            <circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/>
            <circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/>
            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
        </svg>
    </button>

    <div class="tt-backdrop" id="ttBackdrop" onclick="ttClose()"></div>

    <div class="tt-drawer" id="ttDrawer">
        <div class="tt-drawer-title">
            <span>🎨 Theme</span>
            <span id="tt-active-label" style="color:var(--accent);font-size:0.72rem;">Cyber</span>
        </div>
        <div class="tt-drawer-sub">Applies across all pages</div>

        <div class="tt-presets" id="ttPresets"></div>

        <div class="tt-divider"></div>
        <div class="tt-custom-label">Custom Colors</div>

        <div class="tt-custom-row">
            <span class="tt-custom-key">Primary</span>
            <div class="tt-color-wrap">
                <div class="tt-color-preview" id="prev-accent"></div>
                <input type="color" id="col-accent" oninput="ttPreview('accent',this.value)">
            </div>
        </div>
        <div class="tt-custom-row">
            <span class="tt-custom-key">Secondary</span>
            <div class="tt-color-wrap">
                <div class="tt-color-preview" id="prev-accent2"></div>
                <input type="color" id="col-accent2" oninput="ttPreview('accent2',this.value)">
            </div>
        </div>
        <div class="tt-custom-row">
            <span class="tt-custom-key">Background</span>
            <div class="tt-color-wrap">
                <div class="tt-color-preview" id="prev-black"></div>
                <input type="color" id="col-black" oninput="ttPreview('black',this.value)">
            </div>
        </div>
        <div class="tt-custom-row">
            <span class="tt-custom-key">Card BG</span>
            <div class="tt-color-wrap">
                <div class="tt-color-preview" id="prev-card"></div>
                <input type="color" id="col-card" oninput="ttPreview('card',this.value)">
            </div>
        </div>

        <button class="tt-apply-btn" onclick="ttApplyCustom()">✓ Apply Custom</button>
        <button class="tt-reset-btn" onclick="ttReset()">↺ Reset to Default</button>
    </div>
    `
    const wrap = document.createElement('div')
    wrap.innerHTML = html
    document.body.appendChild(wrap)

    // Build preset buttons
    const grid = document.getElementById('ttPresets')
    TT_THEMES.forEach(t => {
        const btn = document.createElement('button')
        btn.className = 'tt-preset-btn'
        btn.dataset.theme = t.id
        btn.onclick = () => { ttApply(t.id); ttClose() }
        btn.innerHTML = `
            <div class="tt-swatch" style="position:relative;">
                <div class="tt-swatch-inner">
                    <div style="background:${t.colors[0]};"></div>
                    <div style="background:${t.colors[1]};"></div>
                    <div style="background:${t.colors[0]}22;grid-column:1/-1;height:50%;margin-top:auto;"></div>
                </div>
                <div class="tt-check">✓</div>
            </div>
            <span class="tt-preset-name">${t.name}</span>
        `
        grid.appendChild(btn)
    })

    ttSyncPickers()
}

// Sync color pickers to current vars
function ttSyncPickers() {
    const r = getComputedStyle(document.documentElement)
    ;[['accent','accent'],['accent2','accent2'],['black','black'],['card','card']].forEach(([key, prop]) => {
        const val = r.getPropertyValue('--'+prop).trim()
        const inp = document.getElementById('col-'+key)
        const prev = document.getElementById('prev-'+key)
        if (inp && isHex(val))   inp.value = val
        if (prev && val) prev.style.background = val
    })
}

function isHex(v) { return /^#[0-9a-f]{3,6}$/i.test(v.trim()) }

// Live preview single var
function ttPreview(key, val) {
    document.documentElement.style.setProperty('--'+key, val)
    if (key === 'accent') {
        document.documentElement.style.setProperty('--cyan', val)
    }
    const prev = document.getElementById('prev-'+key)
    if (prev) prev.style.background = val
}

// Apply all custom colors
function ttApplyCustom() {
    const keys = ['accent','accent2','black','card']
    const custom = {}
    keys.forEach(k => {
        const inp = document.getElementById('col-'+k)
        if (inp) custom[k] = inp.value
    })
    // Derive card2 from card (slightly lighter)
    const r = document.documentElement
    r.style.setProperty('--accent',  custom.accent)
    r.style.setProperty('--accent2', custom.accent2)
    r.style.setProperty('--cyan',    custom.accent)
    r.style.setProperty('--black',   custom.black)
    r.style.setProperty('--card',    custom.card)

    localStorage.setItem('tt_theme', 'custom')
    localStorage.setItem('tt_custom', JSON.stringify(custom))
    document.querySelectorAll('.tt-preset-btn').forEach(b => b.classList.remove('active'))
    const label = document.getElementById('tt-active-label')
    if (label) label.textContent = 'Custom'
    ttClose()
}

// Reset to default (cyber)
function ttReset() {
    localStorage.removeItem('tt_theme')
    localStorage.removeItem('tt_custom')
    ttApply('cyber')
    ttSyncPickers()
    ttClose()
}

// Toggle drawer
function ttToggle() {
    const fab     = document.getElementById('ttFab')
    const drawer  = document.getElementById('ttDrawer')
    const backdrop= document.getElementById('ttBackdrop')
    fab.classList.toggle('open')
    drawer.classList.toggle('open')
    backdrop.classList.toggle('open')
    if (drawer.classList.contains('open')) ttSyncPickers()
}

function ttClose() {
    document.getElementById('ttFab')?.classList.remove('open')
    document.getElementById('ttDrawer')?.classList.remove('open')
    document.getElementById('ttBackdrop')?.classList.remove('open')
}

// Init on load
function ttInit() {
    const saved  = localStorage.getItem('tt_theme')
    const custom = localStorage.getItem('tt_custom')

    if (saved === 'custom' && custom) {
        try {
            const c = JSON.parse(custom)
            const r = document.documentElement
            r.style.setProperty('--accent',  c.accent)
            r.style.setProperty('--accent2', c.accent2)
            r.style.setProperty('--cyan',    c.accent)
            r.style.setProperty('--black',   c.black)
            r.style.setProperty('--card',    c.card)
            document.body.style.background = c.black
        } catch(e) {}
    } else if (saved && TT_VARS[saved]) {
        ttApply(saved)
    } else {
        ttApply('cyber')
    }

    ttBuild()

    // Update active after build
    const active = saved || 'cyber'
    ttUpdateUI(active)
}

document.addEventListener('DOMContentLoaded', ttInit)
