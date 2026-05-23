@extends('layouts.app')
@section('title', __('menu.register_student'))
@section('page-title', __('menu.register_student'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> {{ __('menu.add_student') }}</h3>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="student_id">{{ __('menu.student_id') }} <span class="required">*</span></label>
                    <input type="text" id="student_id" name="student_id"
                           class="form-control @error('student_id') is-invalid @enderror"
                           value="{{ old('student_id') }}" placeholder="{{ __('menu.student_id') }}" required>
                    @error('student_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-8">
                    <label for="name">{{ __('menu.full_name_required') }} <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="{{ __('menu.full_name_placeholder') }}" required>
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="email">{{ __('menu.email') }}</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="{{ __('menu.email_placeholder') }}">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-6">
                    <label for="phone">{{ __('menu.phone') }}</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           value="{{ old('phone') }}" placeholder="{{ __('menu.phone') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="class">{{ __('menu.class_year') }}</label>
                    <input type="text" id="class" name="class" class="form-control"
                           value="{{ old('class') }}" placeholder="{{ __('menu.class_year') }}">
                </div>
                <div class="form-group col-4">
                    <label for="major">{{ __('menu.major') }}</label>
                    <input type="text" id="major" name="major" class="form-control"
                           value="{{ old('major') }}" placeholder="{{ __('menu.major') }}">
                </div>
                <div class="form-group col-4">
                    <label for="status">{{ __('menu.status') }} <span class="required">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>{{ __('menu.status_active') }}</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('menu.status_inactive') }}</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>{{ __('menu.status_suspended') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="address">{{ __('menu.address') }}</label>
                <textarea id="address" name="address" class="form-control" rows="2"
                          placeholder="{{ __('menu.address') }}">{{ old('address') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('menu.register_student_btn') }}
                    </button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection