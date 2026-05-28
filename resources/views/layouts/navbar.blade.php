<div class="lang-switcher">
    <a href="{{ route('lang.switch', 'en') }}" 
       class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">
       {{ __('menu.english') }}
    </a>
    <a href="{{ route('lang.switch', 'kh') }}" 
       class="lang-btn {{ app()->getLocale() == 'kh' ? 'active' : '' }}">
        {{ __('menu.khmer') }}
    </a>
</div>