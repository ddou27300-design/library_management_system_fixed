@extends('layouts.app')
@section('title', 'Add Staff')
@section('page-title', 'Add Staff Account')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> Register New Staff</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('register.post') }}" method="POST" data-validate>
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required placeholder="e.g. John Smith">
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required placeholder="staff@library.com">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row cols-3">
                <div class="form-group">
                    <label for="role">Role <span class="required">*</span></label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="librarian" {{ old('role') === 'librarian' ? 'selected' : '' }}>Librarian</option>
                        <option value="admin"     {{ old('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required placeholder="Min. 8 characters">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" required placeholder="Repeat password">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Account
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection