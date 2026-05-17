@extends('layouts.app')
@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Edit: {{ $category->name }}</h3>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="name">Category Name <span class="required">*</span></label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $category->name) }}" required autofocus>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                          class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('menu.save') }}
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                    {{ __('menu.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
