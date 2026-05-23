@extends('layouts.app')
@section('title', __('menu.add_staff'))
@section('page-title', __('menu.register_title'))

@section('content')
<div style="max-width:560px">
    <div style="margin-bottom:16px">
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back_to_staff') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-plus"></i> {{ __('menu.new_staff_account') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">{{ __('menu.full_name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="{{ __('menu.full_name_placeholder') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.email_address') }} <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="{{ __('menu.email_placeholder_staff') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.role') }} <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">{{ __('menu.select_role') }}</option>
                        <option value="librarian" {{ old('role') === 'librarian' ? 'selected' : '' }}>{{ __('menu.role_librarian') }}</option>
                        <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>{{ __('menu.role_admin') }}</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.password') }} <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="{{ __('menu.password_min') }}" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.confirm_password') }} <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="{{ __('menu.password_placeholder_repeat') }}" required>
                </div>

                <div style="display:flex;gap:10px;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('menu.create_account') }}
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
