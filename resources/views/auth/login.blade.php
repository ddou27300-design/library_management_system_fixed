<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ __('menu.login_title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <style>
        @if(app()->getLocale() === 'kh')
        body, label, input, button, p, h1, small, a {
            font-family: 'Hanuman', sans-serif !important;
        }
        @endif

        /* Staggered fade-in for form fields */
        .form-group { animation: fadeUp .5s ease both; }
        .form-group:nth-child(1) { animation-delay: .1s; }
        .form-group:nth-child(2) { animation-delay: .2s; }
        .form-check { animation: fadeUp .5s ease .3s both; }
        .btn-login  { animation: fadeUp .5s ease .35s both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Language switcher on login page */
        .login-lang {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 4px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 999px;
            padding: 3px 5px;
            backdrop-filter: blur(8px);
        }
        .login-lang-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
            color: rgba(255,255,255,.75);
            white-space: nowrap;
        }
        .login-lang-btn:hover { color: #fff; }
        .login-lang-btn.active {
            background: rgba(255,255,255,.95);
            color: #1a3c5e !important;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
    </style>
</head>
<body>
<div class="login-bg">

    {{-- Language switcher (top-right of page) --}}
        <div class="login-lang">
        <a href="{{ route('lang.switch', 'en') }}"
           class="login-lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
            {{ __('menu.english') }}
        </a>
        <a href="{{ route('lang.switch', 'kh') }}"
           class="login-lang-btn {{ app()->getLocale() === 'kh' ? 'active' : '' }}">
            {{ __('menu.khmer') }}
        </a>
    </div>

    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Library" style="height: 60px; margin-bottom: 8px;">
            <h1>{{ __('menu.library_ms') }}</h1>
            <p>NATIONAL MEANCHEY UNIVERSITY</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="login-form">
            @csrf

            <div class="form-group">
                <label for="email">{{ __('menu.email') }}</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="{{ __('menu.email_placeholder') }}"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <span class="field-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('menu.password') }}</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="{{ __('menu.password_placeholder') }}"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePwd()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">{{ __('menu.remember_me') }}</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> {{ __('menu.sign_in') }}
            </button>
        </form>

    </div>
</div>
<script>
function togglePwd() {
    const f = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') { f.type = 'text';     i.className = 'fas fa-eye-slash'; }
    else                        { f.type = 'password'; i.className = 'fas fa-eye'; }
}
</script>
</body>
</html>
