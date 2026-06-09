/* ============================================================
   Library Management System — App JS
   ============================================================ */

// Sidebar toggle with smooth overlay on mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    sidebar.classList.toggle('open');
    // Create/remove overlay
    let overlay = document.getElementById('sidebar-overlay');
    if (sidebar.classList.contains('open')) {
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'sidebar-overlay';
            overlay.style.cssText = [
                'position:fixed;inset:0;background:rgba(0,0,0,.4);',
                'z-index:99;transition:opacity .3s;cursor:pointer;'
            ].join('');
            overlay.addEventListener('click', () => toggleSidebar());
            document.body.appendChild(overlay);
            requestAnimationFrame(() => overlay.style.opacity = '1');
        }
    } else {
        if (overlay) overlay.remove();
    }
}

// Close sidebar when clicking outside on desktop — using overlay for mobile
document.addEventListener('click', function (e) {
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.querySelector('.sidebar-toggle');
    if (!sidebar || !toggle) return;
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('open');
            const overlay = document.getElementById('sidebar-overlay');
            if (overlay) overlay.remove();
        }
    }
});

// == Confirmation Modal =======================================
const modal        = document.getElementById('confirmModal');
const modalIcon    = document.getElementById('modalIcon');
const modalTitle   = document.getElementById('modalTitle');
const modalMessage = document.getElementById('modalMessage');
const modalConfirm = document.getElementById('modalConfirm');
const modalCancel  = document.getElementById('modalCancel');

let pendingEl = null;

function showConfirmModal({ title, message, icon, accent }) {
    if (!modal) return;
    modalTitle.textContent   = title   || 'Are you sure?';
    modalMessage.textContent = message || 'Do you really want to do this?';
    modalIcon.className      = 'modal-icon ' + (icon || 'danger');
    modalConfirm.className   = 'btn btn-confirm' + (accent ? ' accent' : '');
    modal.classList.add('active');
}

function hideConfirmModal() {
    if (!modal) return;
    modal.classList.remove('active');
    pendingEl = null;
}

modalCancel.addEventListener('click', hideConfirmModal);
modal.addEventListener('click', function (e) {
    if (e.target === modal) hideConfirmModal();
});
modalConfirm.addEventListener('click', function () {
    if (pendingEl) {
        if (pendingEl.tagName === 'FORM') {
            pendingEl.submit();
        } else if (pendingEl.tagName === 'A') {
            window.location.href = pendingEl.href;
        }
    }
    hideConfirmModal();
});

// Esc key to close
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') hideConfirmModal();
});

// Auto-dismiss alerts with smooth animation
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .4s, transform .4s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            setTimeout(function () { alert.remove(); }, 400);
        }, 5000);
    });

    // Confirm elements with data-confirm attribute (forms + links)
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        function confirmHandler(e) {
            // For forms prevent submit; for links prevent navigation
            if (el.tagName === 'FORM') e.preventDefault();
            if (el.tagName === 'A')     e.preventDefault();

            pendingEl = el;
            const raw = el.getAttribute('data-confirm') || '';
            var opts;
            try {
                opts = JSON.parse(raw);
            } catch (_) {
                opts = { message: raw };
            }
            showConfirmModal({
                title:   opts.title   || 'Are you sure?',
                message: opts.message || 'Do you really want to do this?',
                icon:    opts.icon    || 'danger',
                accent:  opts.accent  || false,
            });
        }

        if (el.tagName === 'FORM') {
            el.addEventListener('submit', confirmHandler);
        } else {
            el.addEventListener('click', confirmHandler);
        }
    });

    // Active nav highlight based on URL
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && path.startsWith(href) && href !== '/') {
            link.classList.add('active');
        }
    });
});

// Table row click to navigate
function rowClick(url) {
    window.location.href = url;
}

// Image preview for file inputs
function previewImage(input, targetId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const target = document.getElementById(targetId || 'image-preview');
            if (target) {
                target.style.display = 'block';
                const img = target.querySelector('img');
                if (img) img.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Quick loan period setter for borrow form
function setLoanDays(days) {
    const borrowInput = document.getElementById('borrow_date');
    const dueInput    = document.getElementById('due_date');
    if (!borrowInput || !dueInput) return;
    const bDate = new Date(borrowInput.value);
    if (isNaN(bDate.getTime())) return;
    bDate.setDate(bDate.getDate() + days);
    dueInput.value = bDate.toISOString().split('T')[0];
}

// Real-time fine calculator for return form
function calcFine() {
    const returnInput = document.getElementById('return_date');
    const condInput   = document.getElementById('condition');
    const display     = document.getElementById('fineDisplay');
    if (!returnInput || !condInput || !display) return;

    const dueDate    = new Date(display.dataset.due);
    const returnDate = new Date(returnInput.value);
    const fineRate   = parseFloat(display.dataset.rate || 0.25);
    let fine = 0;

    if (condInput.value === 'lost') {
        fine = 10.00;
    } else if (!isNaN(returnDate.getTime()) && returnDate > dueDate) {
        const diffDays = Math.floor((returnDate - dueDate) / 86400000);
        fine = diffDays * fineRate;
    }

    display.textContent = '$' + fine.toFixed(2);
    display.className   = 'fine-display ' + (fine > 0 ? 'fine-amount-red' : 'fine-amount-zero');
}
