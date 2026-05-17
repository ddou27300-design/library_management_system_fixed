@extends('layouts.app')
@section('title', 'Register Student')
@section('page-title', 'Register New Student')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> New Student</h3>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="student_id">Student ID <span class="required">*</span></label>
                    <input type="text" id="student_id" name="student_id"
                           class="form-control @error('student_id') is-invalid @enderror"
                           value="{{ old('student_id') }}" placeholder="e.g. STU-006" required>
                    @error('student_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-8">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="e.g. Sophea Meas" required>
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="student@university.edu">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-6">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           value="{{ old('phone') }}" placeholder="e.g. 012345678">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="class">Class / Year</label>
                    <input type="text" id="class" name="class" class="form-control"
                           value="{{ old('class') }}" placeholder="e.g. Year 3">
                </div>
                <div class="form-group col-4">
                    <label for="major">Major</label>
                    <input type="text" id="major" name="major" class="form-control"
                           value="{{ old('major') }}" placeholder="e.g. Computer Science">
                </div>
                <div class="form-group col-4">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="2"
                          placeholder="Home address...">{{ old('address') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Register Student
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection