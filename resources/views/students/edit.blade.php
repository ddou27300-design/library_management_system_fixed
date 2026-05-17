@extends('layouts.app')
@section('title', 'Edit Student')
@section('page-title', 'Edit Student')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-user-edit"></i> Edit: {{ $student->name }}</h3>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('students.update', $student) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="student_id">Student ID <span class="required">*</span></label>
                    <input type="text" id="student_id" name="student_id"
                           class="form-control @error('student_id') is-invalid @enderror"
                           value="{{ old('student_id', $student->student_id) }}" required>
                    @error('student_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $student->name) }}" required>
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $student->email) }}">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           value="{{ old('phone', $student->phone) }}">
                </div>
            </div>

            <div class="form-row form-row-3">
                <div class="form-group">
                    <label for="class">Class / Year</label>
                    <input type="text" id="class" name="class" class="form-control"
                           value="{{ old('class', $student->class) }}" placeholder="e.g. Year 3">
                </div>
                <div class="form-group">
                    <label for="major">Major</label>
                    <input type="text" id="major" name="major" class="form-control"
                           value="{{ old('major', $student->major) }}" placeholder="e.g. Computer Science">
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active"    {{ old('status', $student->status) === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="inactive"  {{ old('status', $student->status) === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $student->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="2">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('menu.save') }}
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                    {{ __('menu.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
