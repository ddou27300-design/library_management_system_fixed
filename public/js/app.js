/* ============================================================
   Library Management System — App JS
   ============================================================ */

// Sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.toggle('open');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function (e) {
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.querySelector('.sidebar-toggle');
    if (!sidebar || !toggle) return;
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    }
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .4s';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 400);
        }, 5000);
    });

    // Confirm delete forms
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('submit', function (e) {
            const msg = el.getAttribute('data-confirm') || 'Are you sure?';
            if (!confirm(msg)) e.preventDefault();
        });
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