@extends('layouts.app')
@section('title', 'Add Staff')
@section('page-title', 'Add Staff')

@section('content')
<div style="max-width:560px">
    <div style="margin-bottom:16px">
        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Staff
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-plus"></i> New Staff Account</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Enter full name" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="staff@library.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">— Select Role —</option>
                        <option value="librarian" {{ old('role') === 'librarian' ? 'selected' : '' }}>Librarian</option>
                        <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 8 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Repeat password" required>
                </div>

                <div style="display:flex;gap:10px;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Account
                    </button>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
