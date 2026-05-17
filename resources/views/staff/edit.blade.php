@extends('layouts.app')
@section('title', 'Edit Staff')
@section('page-title', 'Edit Staff')

@section('content')
<div style="max-width:560px">
    <div style="margin-bottom:16px">
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Staff
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
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $staff->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $staff->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="librarian" {{ old('role', $staff->role) === 'librarian' ? 'selected' : '' }}>Librarian</option>
                        <option value="admin"     {{ old('role', $staff->role) === 'admin'     ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr style="margin:20px 0">
                <p class="text-muted" style="font-size:.85rem">Leave password fields blank to keep current password.</p>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 8 characters">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Repeat new password">
                </div>

                <div style="display:flex;gap:10px;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
