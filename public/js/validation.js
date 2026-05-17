/* ============================================================
   Library Management System — Client-side Validation JS
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    // Generic required field check
    function validateForm(form) {
        let valid = true;
        form.querySelectorAll('[required]').forEach(function (field) {
            const wrapper = field.closest('.form-group');
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        return valid;
    }

    // Email format check
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // ISBN format check (ISBN-10 or ISBN-13, digits only or with dashes)
    function isValidISBN(isbn) {
        const cleaned = isbn.replace(/[-\s]/g, '');
        return /^\d{10}$/.test(cleaned) || /^\d{13}$/.test(cleaned);
    }

    // Attach inline validation to all forms with data-validate
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!validateForm(form)) {
                e.preventDefault();
                form.querySelector('.is-invalid')?.focus();
            }
        });

        // Live field validation
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.addEventListener('blur', function () {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }

                if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
                    field.classList.add('is-invalid');
                    showFieldError(field, 'Please enter a valid email address.');
                } else if (field.name === 'isbn' && field.value && !isValidISBN(field.value)) {
                    field.classList.add('is-invalid');
                    showFieldError(field, 'ISBN must be 10 or 13 digits.');
                }
            });
        });
    });

    function showFieldError(field, msg) {
        let err = field.nextElementSibling;
        if (!err || !err.classList.contains('field-error-js')) {
            err = document.createElement('span');
            err.className = 'field-error field-error-js';
            field.after(err);
        }
        err.textContent = msg;
    }

    // Year field max validation
    document.querySelectorAll('input[name="published_year"]').forEach(function (field) {
        const currentYear = new Date().getFullYear();
        field.addEventListener('blur', function () {
            const yr = parseInt(field.value);
            if (field.value && (yr < 1000 || yr > currentYear)) {
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
    });

    // Due date must be after borrow date
    const borrowDateField = document.getElementById('borrow_date');
    const dueDateField    = document.getElementById('due_date');
    if (borrowDateField && dueDateField) {
        borrowDateField.addEventListener('change', function () {
            const bDate = new Date(borrowDateField.value);
            if (!isNaN(bDate.getTime())) {
                // Auto-set due date to +14 days if not already set
                if (!dueDateField.value) {
                    bDate.setDate(bDate.getDate() + 14);
                    dueDateField.value = bDate.toISOString().split('T')[0];
                }
                dueDateField.min = new Date(borrowDateField.value + 'T00:00:00').toISOString().split('T')[0];
            }
        });
    }

    // Password confirmation
    const pwdField    = document.getElementById('password');
    const pwdConfirm  = document.getElementById('password_confirmation');
    if (pwdField && pwdConfirm) {
        function checkPasswords() {
            if (pwdConfirm.value && pwdField.value !== pwdConfirm.value) {
                pwdConfirm.classList.add('is-invalid');
            } else {
                pwdConfirm.classList.remove('is-invalid');
            }
        }
        pwdField.addEventListener('input', checkPasswords);
        pwdConfirm.addEventListener('input', checkPasswords);
    }

    // Student ID auto-format (uppercase, trim)
    const studentIdField = document.getElementById('student_id');
    if (studentIdField) {
        studentIdField.addEventListener('blur', function () {
            studentIdField.value = studentIdField.value.trim().toUpperCase();
        });
    }

    // Total copies must be >= 1
    const copiesField = document.getElementById('total_copies');
    if (copiesField) {
        copiesField.addEventListener('blur', function () {
            if (parseInt(copiesField.value) < 1) {
                copiesField.value = 1;
            }
        });
    }

    // Search form: prevent empty submit
    document.querySelectorAll('.filter-form').forEach(function (form) {
        // Allow clearing search
    });
});