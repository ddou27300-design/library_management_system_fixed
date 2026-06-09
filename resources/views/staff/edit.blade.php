@extends('layouts.app')
@section('title', __('menu.edit_staff'))
@section('page-title', __('menu.edit_staff'))

@section('content')
<div style="max-width:560px">
    <div style="margin-bottom:16px">
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back_to_staff') }}
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-edit"></i> Edit: {{ $staff->name }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.update', $staff) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">{{ __('menu.full_name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $staff->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.email_address') }} <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $staff->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.role') }} <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="librarian" {{ old('role', $staff->role) === 'librarian' ? 'selected' : '' }}>{{ __('menu.role_librarian') }}</option>
                        <option value="admin"     {{ old('role', $staff->role) === 'admin'     ? 'selected' : '' }}>{{ __('menu.role_admin') }}</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr style="margin:20px 0">
                <p class="text-muted" style="font-size:.85rem">{{ __('menu.leave_password_blank') }}</p>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.new_password') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="{{ __('menu.password_min') }}">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('menu.confirm_new_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="{{ __('menu.password_placeholder_repeat') }}">
                </div>

                <div style="display:flex;gap:10px;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('menu.save_changes') }}
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
