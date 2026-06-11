/**
 * sweetalert-helper.js — TopTrade
 * NO confirmButtonColor / cancelButtonColor — CSS vars handle theme
 */

function confirmLogout(formId = 'logout-form') {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You will be logged out of your account!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Logging out...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });
            document.getElementById(formId)?.submit();
        }
    });
}

function confirmDelete(formId, itemName = 'this item') {
    Swal.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert this! ${itemName} will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId)?.submit();
        }
    });
}

function showSuccess(title = 'Success!', message = 'Operation completed successfully') {
    Swal.fire({
        icon: 'success', title, text: message,
        timer: 3000, timerProgressBar: true, showConfirmButton: false,
    });
}

function showError(title = 'Error!', message = 'Something went wrong') {
    Swal.fire({ icon: 'error', title, text: message });
}

function showWarning(title = 'Warning!', message = 'Please check your input') {
    Swal.fire({ icon: 'warning', title, text: message });
}

function showInfo(title = 'Info', message = 'Here is some information') {
    Swal.fire({ icon: 'info', title, text: message });
}

function confirmAction(options = {}) {
    const defaults = {
        title: 'Are you sure?',
        text: 'Do you want to proceed with this action?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        onConfirm: () => {},
    };
    const s = { ...defaults, ...options };
    Swal.fire({
        title: s.title, text: s.text, icon: s.icon,
        showCancelButton: s.showCancelButton,
        confirmButtonText: s.confirmButtonText,
        cancelButtonText: s.cancelButtonText,
    }).then((result) => {
        if (result.isConfirmed && typeof s.onConfirm === 'function') s.onConfirm();
    });
}

function confirmFormSubmit(formId, options = {}) {
    const defaults = {
        title: 'Are you sure?',
        text: 'Do you want to submit this form?',
        icon: 'question',
        confirmText: 'Yes, submit!',
        cancelText: 'Cancel',
    };
    const s = { ...defaults, ...options };
    Swal.fire({
        title: s.title, text: s.text, icon: s.icon,
        showCancelButton: true,
        confirmButtonText: s.confirmText || s.confirmButtonText,
        cancelButtonText: s.cancelText,
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...', text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });
            document.getElementById(formId)?.submit();
        }
    });
}

function showToast(message, type = 'success', position = 'top-end') {
    const Toast = Swal.mixin({
        toast: true, position,
        showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
    });
    Toast.fire({ icon: type, title: message });
}

function promptInput(options = {}) {
    const defaults = {
        title: 'Enter your input',
        input: 'text',
        inputPlaceholder: 'Type here...',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => { if (!value) return 'You need to write something!'; },
        onSubmit: (value) => {},
    };
    const s = { ...defaults, ...options };
    Swal.fire({
        title: s.title, input: s.input,
        inputPlaceholder: s.inputPlaceholder,
        showCancelButton: s.showCancelButton,
        confirmButtonText: s.confirmButtonText,
        cancelButtonText: s.cancelButtonText,
        inputValidator: s.inputValidator,
    }).then((result) => {
        if (result.isConfirmed && typeof s.onSubmit === 'function') s.onSubmit(result.value);
    });
}

function submitWithLoading(formId, options = {}) {
    const defaults = {
        title: 'Processing...',
        text:  'Please wait while we handle your request.',
        icon:  null,
        delay: 3000,
    };
    const settings = { ...defaults, ...options };

    // Read accent from CSS var live
    const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#00f5d4';
    const card   = getComputedStyle(document.documentElement).getPropertyValue('--card').trim()   || '#111119';
    const muted  = getComputedStyle(document.documentElement).getPropertyValue('--muted').trim()  || '#6b6b80';
    const card2  = getComputedStyle(document.documentElement).getPropertyValue('--card2').trim()  || '#16161f';

    Swal.fire({
        title: settings.title,
        html: `
            <div style="font-family:'DM Sans',sans-serif;font-size:0.9rem;color:${muted};line-height:1.5;margin-bottom:1.25rem;">
                ${settings.text}
            </div>
            <div style="background:${card2};border:1px solid rgba(255,255,255,0.1);height:4px;overflow:hidden;position:relative;margin-bottom:.75rem;border-radius:2px;">
                <div style="position:absolute;top:0;left:-55%;width:55%;height:100%;background:linear-gradient(90deg,transparent,${accent},transparent);box-shadow:0 0 8px ${accent};animation:swal-slide 1.2s linear infinite;"></div>
            </div>
            <div style="font-size:0.62rem;letter-spacing:3px;text-transform:uppercase;color:${muted};opacity:0.5;">// Processing</div>
            <style>@keyframes swal-slide{0%{left:-55%}100%{left:110%}}</style>
        `,
        allowOutsideClick: false,
        allowEscapeKey:    false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading(),
    });

    setTimeout(() => {
        const form = document.getElementById(formId);
        if (form) form.submit();
        else { console.error('[submitWithLoading] Form not found:', formId); Swal.close(); }
    }, settings.delay);
}
