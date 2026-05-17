<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Library MS</title>

    {{-- Khmer font (Hanuman) + Latin font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        /* Apply Khmer font when locale is kh */
        @if(app()->getLocale() === 'kh')
        body, .nav-item, .sidebar-brand span, .page-title,
        .btn, .stat-label, .badge, th, td, label, p, h1, h2, h3, h4, small {
            font-family: 'Hanuman', 'DM Sans', sans-serif !important;
        }
        @endif

        /* ── Language Switcher Button ── */
        .lang-switcher {
            display: flex;
            align-items: center;
            gap: 4px;
            background: var(--bg, #f4f6fb);
            border: 1.5px solid var(--border, #dde3ef);
            border-radius: 999px;
            padding: 3px 5px;
        }
        .lang-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
            color: var(--text-muted, #6b7794);
            white-space: nowrap;
        }
        .lang-btn:hover { color: var(--primary, #1a3c5e); }
        .lang-btn.active {
            background: var(--primary, #1a3c5e);
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(26,60,94,.25);
        }
        .lang-flag { font-size: .95rem; }

        /* Nav section labels */
        .nav-section-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            padding: 14px 22px 4px;
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ========== SIDEBAR ========== --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <i class="fas fa-book-open"></i>
        <span>Library MS</span>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section-label">{{ __('menu.main_menu') }}</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>{{ __('menu.dashboard') }}</span>
        </a>

        <div class="nav-section-label">{{ __('menu.catalog') }}</div>

        <a href="{{ route('books.index') }}"
           class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span>{{ __('menu.books') }}</span>
        </a>

        <a href="{{ route('categories.index') }}"
           class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span>{{ __('menu.categories') }}</span>
        </a>

        <div class="nav-section-label">{{ __('menu.members') }}</div>

        <a href="{{ route('students.index') }}"
           class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i>
            <span>{{ __('menu.students') }}</span>
        </a>

        <div class="nav-section-label">{{ __('menu.transactions') }}</div>

        <a href="{{ route('borrows.create') }}"
           class="nav-item {{ request()->routeIs('borrows.create') ? 'active' : '' }}">
            <i class="fas fa-hand-holding-heart"></i>
            <span>{{ __('menu.issue_book') }}</span>
        </a>

        <a href="{{ route('borrows.index') }}"
           class="nav-item {{ request()->routeIs('borrows.index') || request()->routeIs('borrows.show') || request()->routeIs('borrows.return*') ? 'active' : '' }}">
            <i class="fas fa-exchange-alt"></i>
            <span>{{ __('menu.borrow_records') }}</span>
        </a>

        <div class="nav-section-label">{{ __('menu.analytics') }}</div>

        <a href="{{ route('reports.index') }}"
           class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>{{ __('menu.reports') }}</span>
        </a>

        <a href="{{ route('reports.overdue') }}"
           class="nav-item {{ request()->routeIs('reports.overdue') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ __('menu.overdue_books') }}</span>
        </a>

        <a href="{{ route('reports.fines') }}"
           class="nav-item {{ request()->routeIs('reports.fines') ? 'active' : '' }}">
            <i class="fas fa-dollar-sign"></i>
            <span>{{ __('menu.fines') }}</span>
        </a>

        <a href="{{ route('reports.popular') }}"
           class="nav-item {{ request()->routeIs('reports.popular') ? 'active' : '' }}">
            <i class="fas fa-fire"></i>
            <span>{{ __('menu.popular_books') }}</span>
        </a>

        @if(Auth::user()->isAdmin())
        <div class="nav-section-label">{{ __('menu.administration') }}</div>
        <a href="{{ route('staff.index') }}"
           class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i>
            <span>{{ __('menu.staff') }}</span>
        </a>
        <a href="{{ route('staff.create') }}"
           class="nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
            <i class="fas fa-user-plus"></i>
            <span>{{ __('menu.add_staff') }}</span>
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

{{-- ========== MAIN WRAPPER ========== --}}
<div class="main-wrapper">

    {{-- Topbar --}}
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

            {{-- ── Language Switcher ── --}}
            <div class="lang-switcher" title="{{ __('menu.language') }}">
                <a href="{{ route('lang.switch', 'en') }}"
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                    <span>ENGLISH</span>
                </a>
                <a href="{{ route('lang.switch', 'kh') }}"
                   class="lang-btn {{ app()->getLocale() === 'kh' ? 'active' : '' }}">
                    <span>KHMER</span>
                </a>
            </div>

            @if(Auth::user()->isAdmin())
            <a href="{{ route('staff.create') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-user-plus"></i>
                <span>{{ __('menu.add_staff_btn') }}</span>
            </a>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ __('menu.logout') }}</span>
                </button>
            </form>
        </div>
    </header>

    {{-- Flash Messages --}}
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

    {{-- Page Content --}}
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
@stack('scripts')
</body>
</html>
