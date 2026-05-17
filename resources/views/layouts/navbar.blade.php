<div class="language-switcher" style="padding: 5px 10px; display: inline-flex; align-items: center; background: #f1f5f9; border-radius: 30px; gap: 5px; border: 1px solid #e2e8f0;">
    
    <a href="{{ route('lang.switch', 'en') }}" 
       style="text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; transition: all 0.2s ease; 
              {{ app()->getLocale() == 'en' ? 'background: #2563eb; color: #ffffff; box-shadow: 0 2px 5px rgba(37,99,235,0.25);' : 'color: #64748b;' }}">
       ENGLISH
    </a>

    <a href="{{ route('lang.switch', 'kh') }}" 
       style="text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; transition: all 0.2s ease; 
              {{ app()->getLocale() == 'kh' ? 'background: #2563eb; color: #ffffff; box-shadow: 0 2px 5px rgba(37,99,235,0.25);' : 'color: #64748b;' }}">
       KHMER
    </a>

</div>