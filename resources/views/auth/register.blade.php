@extends('layouts.app')
@section('title', __('menu.add_staff'))
@section('page-title', __('menu.register_page_title'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> {{ __('menu.register_heading') }}</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('register.post') }}" method="POST" data-validate>
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label for="name">{{ __('menu.full_name_required') }} <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required placeholder="{{ __('menu.full_name_placeholder') }}">
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="email">{{ __('menu.email') }} <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required placeholder="{{ __('menu.email_placeholder_staff') }}">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row cols-3">
                <div class="form-group">
                    <label for="role">{{ __('menu.role') }} <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="librarian" {{ old('role') === 'librarian' ? 'selected' : '' }}>{{ __('menu.role_librarian') }}</option>
                        <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>{{ __('menu.role_admin') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">{{ __('menu.password') }} <span class="required">*</span></label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required placeholder="{{ __('menu.password_min') }}">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{ __('menu.confirm_password') }} <span class="required">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" required placeholder="{{ __('menu.password_placeholder_repeat') }}">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('menu.create_account') }}
                    </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection