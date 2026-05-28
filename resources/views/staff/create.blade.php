@extends('layouts.app')
@section('title', __('menu.add_staff'))
@section('page-title', __('menu.register_title'))

@push('styles')
<style>
.role-selector {
    display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
}
.role-card {
    position: relative; padding: 18px 16px; border-radius: var(--radius-sm);
    border: 2px solid var(--border); background: var(--surface);
    cursor: pointer; transition: all var(--transition);
    text-align: center;
}
.role-card:hover {
    border-color: var(--primary-light); background: var(--primary-soft);
}
.role-card.selected {
    border-color: var(--primary); background: var(--primary-soft);
    box-shadow: 0 0 0 3px rgba(26,60,94,.12);
}
.role-card input { position: absolute; opacity: 0; pointer-events: none; }
.role-card .role-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px; font-size: 1.2rem;
}
.role-card .role-icon.admin { background: var(--danger-light); color: var(--danger); }
.role-card .role-icon.librarian { background: var(--info-light); color: var(--info); }
.role-card .role-name { font-weight: 600; font-size: .9rem; color: var(--text); }
.role-card .role-desc { font-size: .76rem; color: var(--text-muted); margin-top: 2px; }
.role-card.selected .role-name { color: var(--primary); }

.input-icon-wrap {
    position: relative;
}
.input-icon-wrap .input-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--text-light); font-size: .9rem; pointer-events: none;
    transition: color var(--transition);
}
.input-icon-wrap .form-control { padding-left: 36px; }
.input-icon-wrap .form-control:focus ~ .input-icon { color: var(--primary); }

.password-hint {
    font-size: .75rem; color: var(--text-muted); margin-top: 4px;
    display: flex; align-items: center; gap: 6px;
}
.password-hint i { font-size: .7rem; }

.form-divider {
    border: none; border-top: 1px solid var(--border);
    margin: 22px 0 18px; position: relative;
}

@media (max-width: 480px) {
    .role-selector { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="card form-card" style="max-width:560px">
    <div class="card-header">
        <h3><i class="fas fa-user-plus" style="color:var(--accent);"></i> {{ __('menu.new_staff_account') }}</h3>
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back_to_staff') }}
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('staff.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">{{ __('menu.full_name') }} <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="{{ __('menu.full_name_placeholder') }}" required>
                    <i class="fas fa-user input-icon"></i>
                </div>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('menu.email_address') }} <span class="required">*</span></label>
                <div class="input-icon-wrap">
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="{{ __('menu.email_placeholder_staff') }}" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>{{ __('menu.role') }} <span class="required">*</span></label>
                <div class="role-selector">
                    <label class="role-card {{ old('role') === 'librarian' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="librarian" {{ old('role') === 'librarian' ? 'checked' : '' }} required>
                        <div class="role-icon librarian"><i class="fas fa-book-open"></i></div>
                        <div class="role-name">{{ __('menu.role_librarian') }}</div>
                        <div class="role-desc">{{ __('menu.librarian_desc') }}</div>
                    </label>
                    <label class="role-card {{ old('role') === 'admin' ? 'selected' : '' }}">
                        <input type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
                        <div class="role-icon admin"><i class="fas fa-shield-alt"></i></div>
                        <div class="role-name">{{ __('menu.role_admin') }}</div>
                        <div class="role-desc">{{ __('menu.admin_desc') }}</div>
                    </label>
                </div>
                @error('role')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <hr class="form-divider">

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="password">{{ __('menu.password') }} <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    <div class="password-hint"><i class="fas fa-info-circle"></i> {{ __('menu.password_min') }}</div>
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{ __('menu.confirm_password') }} <span class="required">*</span></label>
                    <div class="input-icon-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control"
                               placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                        <i class="fas fa-check-circle input-icon"></i>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin-top:28px;">
                <button type="submit" class="btn btn-primary btn-lg" style="flex:1;">
                    <i class="fas fa-user-plus"></i> {{ __('menu.create_account') }}
                </button>
                <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-lg">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.role-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.role-card').forEach(function(c) { c.classList.remove('selected'); });
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
    });
});

var pwd = document.getElementById('password');
var pwdConfirm = document.getElementById('password_confirmation');
if (pwd && pwdConfirm) {
    function checkMatch() {
        if (pwdConfirm.value && pwd.value !== pwdConfirm.value) {
            pwdConfirm.classList.add('is-invalid');
        } else {
            pwdConfirm.classList.remove('is-invalid');
        }
    }
    pwd.addEventListener('input', checkMatch);
    pwdConfirm.addEventListener('input', checkMatch);
}
</script>
@endpush
@endsection