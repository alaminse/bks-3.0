/**
 * sweetalert-helper.js
 * TopTrade — Dynamic theme-aware SweetAlert2
 * Reads CSS variables live so theme switcher works automatically
 */

/* ── Get current theme colors from CSS vars ── */
function ttColors() {
    const r = getComputedStyle(document.documentElement);
    return {
        accent:  r.getPropertyValue('--accent').trim()  || '#00f5d4',
        accent2: r.getPropertyValue('--accent2').trim() || '#6366f1',
        card:    r.getPropertyValue('--card').trim()    || '#111119',
        card2:   r.getPropertyValue('--card2').trim()   || '#16161f',
        border2: r.getPropertyValue('--border2').trim() || 'rgba(255,255,255,0.13)',
        text:    r.getPropertyValue('--text').trim()    || '#e8e8f0',
        muted:   r.getPropertyValue('--muted').trim()   || '#6b6b80',
        red:     r.getPropertyValue('--red').trim()     || '#ef4444',
        gold:    r.getPropertyValue('--gold').trim()    || '#f59e0b',
        blue:    r.getPropertyValue('--blue').trim()    || '#3b82f6',
        green:   r.getPropertyValue('--green').trim()   || '#22c55e',
    };
}

/* ── Inject/update theme styles into <head> ── */
function ttInjectSwalStyles() {
    const c = ttColors();
    let s = document.getElementById('swal-tt-dynamic');
    if (!s) {
        s = document.createElement('style');
        s.id = 'swal-tt-dynamic';
        document.head.appendChild(s);
    }
    s.textContent = `
        .swal2-popup {
            background: ${c.card} !important;
            border: 1px solid ${c.border2} !important;
            border-radius: 16px !important;
            color: ${c.text} !important;
            font-family: 'DM Sans', sans-serif !important;
            box-shadow: 0 24px 64px rgba(0,0,0,0.8) !important;
        }
        .swal2-title {
            font-family: 'Syne', sans-serif !important;
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: ${c.text} !important;
            letter-spacing: 0 !important;
            text-transform: none !important;
        }
        .swal2-html-container {
            color: ${c.muted} !important;
            font-size: 0.9rem !important;
            font-family: 'DM Sans', sans-serif !important;
        }
        .swal2-confirm {
            background: ${c.accent} !important;
            color: #000 !important;
            border: none !important;
            border-radius: 9px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 0.88rem !important;
            font-weight: 700 !important;
            padding: 10px 24px !important;
            clip-path: none !important;
            letter-spacing: 0 !important;
        }
        .swal2-confirm:hover { opacity: 0.9 !important; }
        .swal2-cancel {
            background: ${c.card2} !important;
            color: ${c.muted} !important;
            border: 1px solid ${c.border2} !important;
            border-radius: 9px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 0.88rem !important;
            clip-path: none !important;
            letter-spacing: 0 !important;
        }
        .swal2-cancel:hover {
            color: ${c.text} !important;
            border-color: ${c.accent} !important;
        }
        .swal2-deny {
            background: rgba(239,68,68,0.12) !important;
            color: ${c.red} !important;
            border: 1px solid rgba(239,68,68,0.3) !important;
            border-radius: 9px !important;
            clip-path: none !important;
        }
        /* Icons */
        .swal2-icon.swal2-success { border-color: ${c.accent} !important; color: ${c.accent} !important; }
        .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: ${c.accent} !important; }
        .swal2-icon.swal2-success .swal2-success-ring { border-color: ${c.accent}44 !important; }
        .swal2-icon.swal2-warning { border-color: ${c.gold}  !important; color: ${c.gold}  !important; }
        .swal2-icon.swal2-error   { border-color: ${c.red}   !important; color: ${c.red}   !important; }
        .swal2-icon.swal2-error [class^='swal2-x-mark-line'] { background-color: ${c.red} !important; }
        .swal2-icon.swal2-info    { border-color: ${c.blue}  !important; color: ${c.blue}  !important; }
        .swal2-icon.swal2-question{ border-color: ${c.accent}!important; color: ${c.accent}!important; }
        /* Input */
        .swal2-input, .swal2-textarea, .swal2-select {
            background: ${c.card2} !important;
            border: 1px solid ${c.border2} !important;
            border-radius: 9px !important;
            color: ${c.text} !important;
            font-family: 'DM Sans', sans-serif !important;
        }
        .swal2-input:focus, .swal2-textarea:focus {
            border-color: ${c.accent} !important;
            box-shadow: 0 0 0 3px ${c.accent}22 !important;
        }
        /* Validation */
        .swal2-validation-message {
            background: rgba(239,68,68,0.1) !important;
            color: ${c.red} !important;
            font-family: 'DM Sans', sans-serif !important;
            border-radius: 6px !important;
        }
        /* Timer bar */
        .swal2-timer-progress-bar { background: ${c.accent} !important; }
        /* Close btn */
        .swal2-close { color: ${c.muted} !important; }
        .swal2-close:hover { color: ${c.text} !important; }
        /* Backdrop */
        .swal2-backdrop-show { background: rgba(0,0,0,0.8) !important; }
        /* Toast */
        .swal2-toast.swal2-popup {
            background: ${c.card2} !important;
            border: 1px solid ${c.border2} !important;
            border-radius: 10px !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.6) !important;
        }
        .swal2-toast .swal2-title {
            font-family: 'DM Sans', sans-serif !important;
            font-size: 0.88rem !important;
            font-weight: 600 !important;
            color: ${c.text} !important;
            letter-spacing: 0 !important;
            text-transform: none !important;
        }
        .swal2-toast .swal2-timer-progress-bar { background: ${c.accent} !important; }
        .swal2-toast .swal2-icon.swal2-success { border-color: ${c.accent} !important; }
        .swal2-toast .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: ${c.accent} !important; }
    `;
}

/* ── Wrap Swal.fire to always inject fresh styles ── */
function swalFire(opts) {
    ttInjectSwalStyles();
    const c = ttColors();
    return Swal.fire({
        background: c.card,
        color:      c.text,
        ...opts,
    });
}

/* ── Toast ── */
function showToast(message, type = 'success', position = 'top-end') {
    ttInjectSwalStyles();
    const c = ttColors();
    Swal.fire({
        toast: true, position,
        icon: type, title: message,
        showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
        background: c.card2, color: c.text,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
}

/* ── Success ── */
function showSuccess(title = 'Success!', message = '') {
    swalFire({ icon: 'success', title, text: message, timer: 3000, timerProgressBar: true, showConfirmButton: false });
}

/* ── Error ── */
function showError(title = 'Error!', message = 'Something went wrong') {
    swalFire({ icon: 'error', title, text: message });
}

/* ── Warning ── */
function showWarning(title = 'Warning!', message = '') {
    swalFire({ icon: 'warning', title, text: message });
}

/* ── Info ── */
function showInfo(title = 'Info', message = '') {
    swalFire({ icon: 'info', title, text: message });
}

/* ── Logout ── */
function confirmLogout(formId = 'logout-form') {
    swalFire({
        title: 'Logout?',
        text:  'Are you sure you want to logout?',
        icon:  'warning',
        showCancelButton:  true,
        confirmButtonText: 'Yes, Logout',
        cancelButtonText:  'Cancel',
    }).then(r => {
        if (r.isConfirmed) {
            const c = ttColors();
            Swal.fire({
                title: 'Logging out...',
                background: c.card, color: c.text,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });
            document.getElementById(formId)?.submit();
        }
    });
}

/* ── Delete ── */
function confirmDelete(formId, itemName = 'this item') {
    swalFire({
        title: 'Delete?',
        text:  `${itemName} will be permanently deleted.`,
        icon:  'warning',
        showCancelButton:  true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText:  'Cancel',
    }).then(r => { if (r.isConfirmed) document.getElementById(formId)?.submit(); });
}

/* ── Form Submit with Confirm ── */
function confirmFormSubmit(formId, options = {}) {
    const o = {
        title:       'Are you sure?',
        text:        'Do you want to proceed?',
        icon:        'question',
        confirmText: 'Yes, Submit',
        cancelText:  'Cancel',
        ...options,
    };
    swalFire({
        title:             o.title,
        text:              o.text,
        icon:              o.icon,
        showCancelButton:  true,
        confirmButtonText: o.confirmText,
        cancelButtonText:  o.cancelText,
    }).then(r => {
        if (r.isConfirmed) {
            const c = ttColors();
            Swal.fire({
                title: 'Processing...', text: 'Please wait',
                background: c.card, color: c.text,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });
            document.getElementById(formId)?.submit();
        }
    });
}

/* ── Generic Confirm ── */
function confirmAction(options = {}) {
    const o = {
        title:      'Are you sure?',
        text:       'Do you want to proceed?',
        icon:       'question',
        confirmText:'Yes',
        cancelText: 'Cancel',
        onConfirm:  () => {},
        ...options,
    };
    swalFire({
        title:             o.title,
        text:              o.text,
        icon:              o.icon,
        showCancelButton:  true,
        confirmButtonText: o.confirmText,
        cancelButtonText:  o.cancelText,
    }).then(r => { if (r.isConfirmed) o.onConfirm(); });
}

/* ── Input Prompt ── */
function promptInput(options = {}) {
    const o = {
        title:       'Enter value',
        input:       'text',
        placeholder: 'Type here...',
        confirmText: 'Submit',
        cancelText:  'Cancel',
        validator:   v => !v ? 'Please enter a value' : null,
        onSubmit:    () => {},
        ...options,
    };
    swalFire({
        title:            o.title,
        input:            o.input,
        inputPlaceholder: o.placeholder,
        showCancelButton: true,
        confirmButtonText: o.confirmText,
        cancelButtonText:  o.cancelText,
        inputValidator:    o.validator,
    }).then(r => { if (r.isConfirmed) o.onSubmit(r.value); });
}

/* ── Loading overlay ── */
function submitWithLoading(formId, options = {}) {
    const o = { title: 'Processing...', text: 'Please wait...', delay: 2000, ...options };
    const c = ttColors();
    Swal.fire({
        title: o.title,
        html: `
            <div style="font-family:'DM Sans',sans-serif;font-size:0.88rem;color:${c.muted};margin-bottom:16px;">${o.text}</div>
            <div style="background:${c.card2};border:1px solid ${c.border2};height:4px;overflow:hidden;position:relative;margin-bottom:10px;border-radius:2px;">
                <div style="position:absolute;top:0;left:-55%;width:55%;height:100%;background:linear-gradient(90deg,transparent,${c.accent},transparent);box-shadow:0 0 8px ${c.accent};animation:swal-loading-slide 1.2s linear infinite;border-radius:2px;"></div>
            </div>
            <style>@keyframes swal-loading-slide{0%{left:-55%}100%{left:110%}}</style>
        `,
        background: c.card, color: c.text,
        allowOutsideClick: false,
        allowEscapeKey:    false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading(),
    });
    setTimeout(() => {
        const form = document.getElementById(formId);
        form ? form.submit() : Swal.close();
    }, o.delay);
}

/* ── Init: inject styles on load & re-inject when theme changes ── */
document.addEventListener('DOMContentLoaded', () => {
    ttInjectSwalStyles();

    // Watch for theme changes via MutationObserver on :root style
    const observer = new MutationObserver(() => ttInjectSwalStyles());
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['style']
    });
});
