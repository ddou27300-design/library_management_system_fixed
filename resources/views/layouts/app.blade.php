<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Library MS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        /* Khmer font switching via JS class */
        body.lang-kh,
        body.lang-kh .nav-item,
        body.lang-kh .sidebar-brand span,
        body.lang-kh .page-title,
        body.lang-kh .btn,
        body.lang-kh .stat-label,
        body.lang-kh .badge,
        body.lang-kh th, body.lang-kh td,
        body.lang-kh label, body.lang-kh p,
        body.lang-kh h1, body.lang-kh h2, body.lang-kh h3, body.lang-kh h4, body.lang-kh small,
        body.lang-kh [data-i18n] {
            font-family: 'Hanuman', 'DM Sans', sans-serif !important;
        }

        .lang-switcher {
            display: flex; align-items: center; gap: 4px;
            background: var(--bg, #f4f6fb);
            border: 1.5px solid var(--border, #dde3ef);
            border-radius: 999px; padding: 3px 5px;
        }
        .lang-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 13px; border-radius: 999px;
            font-size: .8rem; font-weight: 700;
            border: none; background: transparent; cursor: pointer;
            transition: all .2s ease;
            color: var(--text-muted, #6b7794);
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        }
        .lang-btn:hover { color: var(--primary, #1a3c5e); }
        .lang-btn.active {
            background: var(--primary, #1a3c5e);
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(26,60,94,.25);
        }
        .lang-btn:disabled { opacity: .65; cursor: not-allowed; }

        .lang-spinner {
            display: inline-block; width: 11px; height: 11px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: currentColor;
            border-radius: 50%;
            animation: lspin .6s linear infinite;
        }
        @keyframes lspin { to { transform: rotate(360deg); } }

        #translate-toast {
            position: fixed; bottom: 24px; right: 24px;
            background: #1a3c5e; color: #fff;
            padding: 11px 18px; border-radius: 10px;
            font-size: .85rem; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            z-index: 9999; opacity: 0; transform: translateY(8px);
            transition: all .3s ease; pointer-events: none;
            max-width: 320px;
        }
        #translate-toast.show    { opacity: 1; transform: translateY(0); }
        #translate-toast.error   { background: #dc2626; }
        #translate-toast.success { background: #16a34a; }

        .nav-section-label {
            font-size: .68rem; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: rgba(255,255,255,.35);
            padding: 14px 22px 4px;
        }
    </style>
    @stack('styles')
</head>
<body class="{{ app()->getLocale() === 'kh' ? 'lang-kh' : 'lang-en' }}" id="app-body">

{{-- Toast notification --}}
<div id="translate-toast">
    <span class="lang-spinner" id="toast-spinner"></span>
    <span id="translate-toast-msg">Translating…</span>
</div>

{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <i class="fas fa-book-open"></i>
        <span>Library MS</span>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section-label" data-i18n="main_menu">{{ __('menu.main_menu') }}</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span data-i18n="dashboard">{{ __('menu.dashboard') }}</span>
        </a>

        <div class="nav-section-label" data-i18n="catalog">{{ __('menu.catalog') }}</div>

        <a href="{{ route('books.index') }}" class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span data-i18n="books">{{ __('menu.books') }}</span>
        </a>

        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span data-i18n="categories">{{ __('menu.categories') }}</span>
        </a>

        <div class="nav-section-label" data-i18n="members">{{ __('menu.members') }}</div>

        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i>
            <span data-i18n="students">{{ __('menu.students') }}</span>
        </a>

        <div class="nav-section-label" data-i18n="transactions">{{ __('menu.transactions') }}</div>

        <a href="{{ route('borrows.create') }}" class="nav-item {{ request()->routeIs('borrows.create') ? 'active' : '' }}">
            <i class="fas fa-hand-holding-heart"></i>
            <span data-i18n="issue_book">{{ __('menu.issue_book') }}</span>
        </a>

        <a href="{{ route('borrows.index') }}" class="nav-item {{ request()->routeIs('borrows.index') || request()->routeIs('borrows.show') || request()->routeIs('borrows.return*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i>
            <span data-i18n="borrow_records">{{ __('menu.borrow_records') }}</span>
        </a>

        <div class="nav-section-label" data-i18n="analytics">{{ __('menu.analytics') }}</div>

        <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span data-i18n="reports">{{ __('menu.reports') }}</span>
        </a>

        <a href="{{ route('reports.overdue') }}" class="nav-item {{ request()->routeIs('reports.overdue') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span data-i18n="overdue_books">{{ __('menu.overdue_books') }}</span>
        </a>

        <a href="{{ route('reports.fines') }}" class="nav-item {{ request()->routeIs('reports.fines') ? 'active' : '' }}">
            <i class="fas fa-dollar-sign"></i>
            <span data-i18n="fines">{{ __('menu.fines') }}</span>
        </a>

        <a href="{{ route('reports.popular') }}" class="nav-item {{ request()->routeIs('reports.popular') ? 'active' : '' }}">
            <i class="fas fa-fire"></i>
            <span data-i18n="popular_books">{{ __('menu.popular_books') }}</span>
        </a>

        @if(Auth::user()->isAdmin())
        <div class="nav-section-label" data-i18n="administration">{{ __('menu.administration') }}</div>
        <a href="{{ route('staff.index') }}" class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i>
            <span data-i18n="staff">{{ __('menu.staff') }}</span>
        </a>
        <a href="{{ route('staff.create') }}" class="nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i>
            <span data-i18n="add_staff">{{ __('menu.add_staff') }}</span>
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-badge">
            <i class="fas fa-user-circle"></i>
            <div>
                <div style="color:#e2e8f0;font-size:13px;font-weight:600;">{{ Auth::user()->name }}</div>
                <div style="font-size:11px;color:#64748b;text-transform:capitalize;">{{ Auth::user()->role }}</div>
            </div>
        </div>
    </div>
</aside>

{{-- ===== MAIN WRAPPER ===== --}}
<div class="main-wrapper">

    <header class="topbar">
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Menu">
            <i class="fas fa-bars"></i>
        </button>

        <h1 class="page-title">@yield('page-title', __('menu.dashboard'))</h1>

        <div class="topbar-right">
            <span class="topbar-date">
                <i class="fas fa-calendar-alt"></i>
                {{ now()->format('D, d M Y') }}
            </span>

            {{-- AI Language Switcher --}}
            <div class="lang-switcher" title="AI-powered language switcher">
                <button id="btn-lang-en"
                        class="lang-btn {{ app()->getLocale() !== 'kh' ? 'active' : '' }}"
                        onclick="switchLanguage('en')">
                    ENGLISH
                </button>
                <button id="btn-lang-kh"
                        class="lang-btn {{ app()->getLocale() === 'kh' ? 'active' : '' }}"
                        onclick="switchLanguage('kh')">
                    ខ្មែរ
                </button>
            </div>

            @if(Auth::user()->isAdmin())
            <a href="{{ route('staff.create') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-user-plus"></i>
                <span data-i18n="add_staff_btn">{{ __('menu.add_staff_btn') }}</span>
            </a>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span data-i18n="logout">{{ __('menu.logout') }}</span>
                </button>
            </form>
        </div>
    </header>

    <div class="flash-container">
        @foreach(['success' => 'check-circle', 'error' => 'exclamation-circle', 'info' => 'info-circle', 'warning' => 'exclamation-triangle'] as $type => $icon)
            @if(session($type))
                <div class="alert alert-{{ $type }}">
                    <i class="fas fa-{{ $icon }}"></i>
                    {{ session($type) }}
                    <button onclick="this.parentElement.remove()" class="alert-close">&times;</button>
                </div>
            @endif
        @endforeach
    </div>

    <main class="page-content">
        @yield('content')
    </main>

    <footer class="page-footer">
        <p>&copy; {{ date('Y') }} Library Management System. All rights reserved.</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/validation.js') }}"></script>

<script>
/* ================================================================
   AI Live Translation System — powered by Gemini API
   Switches all [data-i18n] elements without reloading the page.
================================================================ */
(function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // --- Session cache helpers ---
    const cache = {
        get: (locale) => { try { return JSON.parse(sessionStorage.getItem('lms_t_' + locale)); } catch { return null; } },
        set: (locale, data) => { try { sessionStorage.setItem('lms_t_' + locale, JSON.stringify(data)); } catch {} },
    };

    // --- Toast helpers ---
    const toast = document.getElementById('translate-toast');
    const toastMsg = document.getElementById('translate-toast-msg');
    const toastSpinner = document.getElementById('toast-spinner');
    let toastTimer = null;

    function showToast(msg, type) {
        clearTimeout(toastTimer);
        toast.className = 'show' + (type && type !== 'loading' ? ' ' + type : '');
        toastMsg.textContent = msg;
        toastSpinner.style.display = (type === 'loading') ? 'inline-block' : 'none';
        if (type !== 'loading') {
            toastTimer = setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }
    }

    // --- Apply translations to DOM ---
    function applyTranslations(translations, locale) {
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (translations[key] !== undefined) {
                el.textContent = translations[key];
            }
        });
        const body = document.getElementById('app-body');
        body.className = body.className.replace(/\blang-\w+/g, '').trim() + ' lang-' + locale;
        document.getElementById('html-root').lang = (locale === 'kh') ? 'km' : 'en';

        const btnEn = document.getElementById('btn-lang-en');
        const btnKh = document.getElementById('btn-lang-kh');
        btnEn.classList.toggle('active', locale === 'en');
        btnKh.classList.toggle('active', locale === 'kh');
    }

    // --- Set button loading state ---
    function setLoading(locale, loading) {
        const btn = document.getElementById(locale === 'kh' ? 'btn-lang-kh' : 'btn-lang-en');
        const other = document.getElementById(locale === 'kh' ? 'btn-lang-en' : 'btn-lang-kh');
        if (loading) {
            btn.innerHTML = '<span class="lang-spinner"></span> Translating…';
            btn.disabled = true;
            other.disabled = true;
        } else {
            btn.innerHTML = locale === 'kh' ? 'ខ្មែរ' : 'ENGLISH';
            btn.disabled = false;
            other.disabled = false;
        }
    }

    // --- Main switch function (exposed globally) ---
    window.switchLanguage = async function (locale) {
        const activeBtn = document.querySelector('.lang-btn.active');
        const currentLocale = activeBtn ? (activeBtn.id === 'btn-lang-en' ? 'en' : 'kh') : 'en';
        if (locale === currentLocale) return;

        /* =========================================================================
           COMMENTED OUT FOR DEVELOPMENT:
           លុបចោលការទាញ Cache ចេញ ដើម្បីបង្ខំឱ្យវាហៅ API ទាញយកពាក្យថ្មីៗជានិច្ចពេលកូដ Blade ប្រែប្រួល
           =========================================================================
        const cached = cache.get(locale);
        if (cached) {
            applyTranslations(cached, locale);
            showToast(locale === 'kh' ? '✓ ប្ដូរទៅភាសាខ្មែរ' : '✓ Switched to English', 'success');
            fetch('/translate/' + (locale === 'kh' ? 'khmer' : 'english'), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            }).catch(() => {});
            return;
        }
        ========================================================================= */

        // Fetch from server (calls Gemini API)
        setLoading(locale, true);
        showToast(
            locale === 'kh' ? 'Gemini AI is translating to Khmer…' : 'Switching to English…',
            'loading'
        );

        try {
            const endpoint = '/translate/' + (locale === 'kh' ? 'khmer' : 'english');
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            });

            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.message || 'HTTP ' + res.status);
            }

            const data = await res.json();
            cache.set(locale, data.translations);
            applyTranslations(data.translations, locale);
            showToast(
                locale === 'kh' ? '✓ បានបកប្រែដោយ Gemini AI' : '✓ Switched to English',
                'success'
            );
        } catch (err) {
            console.error('[Translation]', err);
            showToast('Translation failed: ' + err.message, 'error');
        } finally {
            setLoading(locale, false);
        }
    };

    // Re-apply cached translation on page load (if locale was already kh)
    document.addEventListener('DOMContentLoaded', () => {
        const serverLocale = document.getElementById('app-body').classList.contains('lang-kh') ? 'kh' : 'en';
        if (serverLocale === 'kh') {
            const cached = cache.get('kh');
            if (cached) applyTranslations(cached, 'kh');
        }
    });
})();
</script>

@stack('scripts')
</body>
</html>