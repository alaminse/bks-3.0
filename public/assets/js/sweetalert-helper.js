/**
 * Reusable SweetAlert Functions
 * Place this file in: public/assets/js/sweetalert-helper.js
 */

// Logout Confirmation
function confirmLogout(formId = 'logout-form') {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of your account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Logging out...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById(formId).submit();
        }
    });
}

// Delete Confirmation
function confirmDelete(formId, itemName = 'this item') {
    Swal.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert this! ${itemName} will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Success Alert
function showSuccess(title = 'Success!', message = 'Operation completed successfully') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonColor: '#3085d6',
        timer: 3000,
        timerProgressBar: true
    });
}

// Error Alert
function showError(title = 'Error!', message = 'Something went wrong') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonColor: '#d33'
    });
}

// Warning Alert
function showWarning(title = 'Warning!', message = 'Please check your input') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        confirmButtonColor: '#f0ad4e'
    });
}

// Info Alert
function showInfo(title = 'Info', message = 'Here is some information') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonColor: '#3085d6'
    });
}

// Generic Confirmation
function confirmAction(options = {}) {
    const defaults = {
        title: 'Are you sure?',
        text: 'Do you want to proceed with this action?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel',
        onConfirm: () => {}
    };

    const settings = { ...defaults, ...options };

    Swal.fire({
        title: settings.title,
        text: settings.text,
        icon: settings.icon,
        showCancelButton: settings.showCancelButton,
        confirmButtonColor: settings.confirmButtonColor,
        cancelButtonColor: settings.cancelButtonColor,
        confirmButtonText: settings.confirmButtonText,
        cancelButtonText: settings.cancelButtonText
    }).then((result) => {
        if (result.isConfirmed && typeof settings.onConfirm === 'function') {
            settings.onConfirm();
        }
    });
}

// Form Submit with Confirmation
function confirmFormSubmit(formId, options = {}) {
    const defaults = {
        title: 'Are you sure?',
        text: 'Do you want to submit this form?',
        icon: 'question',
        confirmButtonText: 'Yes, submit!'
    };

    const settings = { ...defaults, ...options };

    Swal.fire({
        title: settings.title,
        text: settings.text,
        icon: settings.icon,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: settings.confirmButtonText,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById(formId).submit();
        }
    });
}

// Toast Notification
function showToast(message, type = 'success', position = 'top-end') {
    const Toast = Swal.mixin({
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

// Input Prompt
function promptInput(options = {}) {
    const defaults = {
        title: 'Enter your input',
        input: 'text',
        inputPlaceholder: 'Type here...',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value) {
                return 'You need to write something!';
            }
        },
        onSubmit: (value) => {}
    };

    const settings = { ...defaults, ...options };

    Swal.fire({
        title: settings.title,
        input: settings.input,
        inputPlaceholder: settings.inputPlaceholder,
        showCancelButton: settings.showCancelButton,
        confirmButtonText: settings.confirmButtonText,
        cancelButtonText: settings.cancelButtonText,
        inputValidator: settings.inputValidator
    }).then((result) => {
        if (result.isConfirmed && typeof settings.onSubmit === 'function') {
            settings.onSubmit(result.value);
        }
    });
}

// $('form').on('submit', function (e) {
//     e.preventDefault(); // ⛔ stop instant submit

//     Swal.fire({
//         title: 'Processing Deposit',
//         text: 'Please wait while we verify your request...',
//         allowOutsideClick: false,
//         allowEscapeKey: false,
//         didOpen: () => {
//             Swal.showLoading();
//         }
//     });

//     // ⏳ .5 seconds delay
//     setTimeout(() => {
//         e.target.submit(); // ✅ submit after 5 sec
//     }, 500);
// });


// function submitWithLoading(formId, options = {}) {
//     const defaults = {
//         title: 'Processing...',
//         text: 'Please wait...',
//         icon: null,
//         delay: 3000
//     };

//     const settings = { ...defaults, ...options };

//     Swal.fire({
//         title: settings.title,
//         text: settings.text,
//         icon: settings.icon,
//         allowOutsideClick: false,
//         allowEscapeKey: false,
//         didOpen: () => {
//             Swal.showLoading();
//         }
//     });

//     setTimeout(() => {
//         const form = document.getElementById(formId);
//         if (form) {
//             form.submit();
//         } else {
//             console.error('Form not found:', formId);
//         }
//     }, settings.delay);
// }

/**
 * submitWithLoading — Cyberpunk SweetAlert2 loading overlay
 * Matches dashboard design system (Orbitron + cyan/blue palette)
 */
function submitWithLoading(formId, options = {}) {
    const defaults = {
        title: 'Processing...',
        text:  'Please wait while we handle your request.',
        icon:  null,
        delay: 3000,
        // extra options
        confirmColor: null, // override accent color e.g. '#fbbf24' for amber
    };

    const settings = { ...defaults, ...options };
    const accent   = settings.confirmColor || '#00f5ff';
    const isDark   = accent === '#00f5ff'; // cyan = dark text, others = white

    Swal.fire({
        title: settings.title,
        html: `
            <div style="
                font-family:'Rajdhani',sans-serif;
                font-size:.95rem;
                color:#5a8aaa;
                line-height:1.5;
                margin-bottom:1.25rem;
            ">${settings.text}</div>

            <!-- animated bar -->
            <div style="
                background:#011535;
                border:1px solid rgba(0,245,255,.15);
                height:4px;
                overflow:hidden;
                position:relative;
                margin-bottom:.75rem;
            ">
                <div id="swal-progress-fill" style="
                    position:absolute;
                    top:0; left:-50%;
                    width:50%; height:100%;
                    background:linear-gradient(90deg, transparent, ${accent}, transparent);
                    box-shadow:0 0 8px ${accent};
                    animation:swal-slide 1.2s linear infinite;
                "></div>
            </div>

            <div style="
                font-family:'Orbitron',monospace;
                font-size:.46rem;
                letter-spacing:3px;
                text-transform:uppercase;
                color:rgba(0,245,255,.35);
            ">// Standby</div>

            <style>
                @keyframes swal-slide {
                    0%   { left: -55%; }
                    100% { left: 110%; }
                }
            </style>
        `,
        icon: settings.icon || undefined,
        allowOutsideClick: false,
        allowEscapeKey:    false,
        showConfirmButton: false,
        background:        '#010e24',
        color:             '#c8e8ff',
        customClass: {
            popup:  'swal-cyber-popup',
            title:  'swal-cyber-title',
        },
        didOpen: () => {
            // inject extra popup styles once
            if (!document.getElementById('swal-cyber-style')) {
                const s = document.createElement('style');
                s.id = 'swal-cyber-style';
                s.textContent = `
                    .swal-cyber-popup {
                        background: #010e24 !important;
                        border: 1px solid rgba(0,245,255,.45) !important;
                        border-top: 2px solid ${accent} !important;
                        border-radius: 0 !important;
                        box-shadow: 0 0 40px rgba(0,245,255,.15), 0 0 80px rgba(0,245,255,.06) !important;
                        padding: 2rem 1.75rem 1.75rem !important;
                    }
                    .swal-cyber-title {
                        font-family: 'Orbitron', monospace !important;
                        font-size: .85rem !important;
                        font-weight: 700 !important;
                        letter-spacing: 2px !important;
                        text-transform: uppercase !important;
                        color: #ffffff !important;
                        margin-bottom: .5rem !important;
                    }
                    .swal2-loading .swal2-styled.swal2-confirm { display: none !important; }
                `;
                document.head.appendChild(s);
            }
            Swal.showLoading();
        }
    });

    setTimeout(() => {
        const form = document.getElementById(formId);
        if (form) {
            form.submit();
        } else {
            console.error('[submitWithLoading] Form not found:', formId);
            Swal.close();
        }
    }, settings.delay);
}


/* ─────────────────────────────────────────────
   USAGE EXAMPLES
   ─────────────────────────────────────────────

// Default cyan — general form submit
submitWithLoading('checkout-form');

// Custom title + text
submitWithLoading('withdraw-form', {
    title: 'Submitting Withdrawal...',
    text:  'Your withdrawal request is being processed.',
    delay: 2500,
});

// Amber accent — warning/caution action
submitWithLoading('delete-form', {
    title: 'Deleting Account...',
    text:  'This action cannot be undone.',
    delay: 2000,
    confirmColor: '#fbbf24',
});

// Green accent — success/confirm action
submitWithLoading('package-form', {
    title: 'Purchasing Package...',
    text:  'Activating your package now.',
    delay: 3000,
    confirmColor: '#34d399',
});

───────────────────────────────────────────── */

