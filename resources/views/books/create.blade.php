@extends('layouts.app')

@section('content')
<style>
    .form-container {
        background: #ffffff;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .full-width {
        grid-column: span 2;
    }
    .field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .field-label {
        font-weight: 600;
        font-size: 14px;
        color: #333333;
    }
    .input-style {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        background-color: #fff;
        outline: none;
        box-sizing: border-box;
    }
    .input-style:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }
    .btn-submit {
        background-color: #2563eb;
        color: #ffffff;
        padding: 8px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
    }
    .btn-submit:hover {
        background-color: #1d4ed8;
    }
    .btn-cancel {
        background-color: #f3f4f6;
        color: #4b5563;
        padding: 8px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
    }
    .btn-cancel:hover {
        background-color: #e5e7eb;
    }
    .error-box {
        background-color: #fef2f2;
        border: 1px solid #fee2e2;
        color: #991b1b;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
</style>

<div class="form-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 700; margin: 0; color: #111827;">Add New Book</h2>
        <a href="{{ route('books.index') }}" style="color: #4f46e5; text-decoration: none; font-size: 14px; font-weight: 500;">
            ← Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="error-box">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <div class="field-group">
                <label for="title" class="field-label">Title <span style="color: #dc2626;">*</span></label>
                <input type="text" name="title" id="title" class="input-style" value="{{ old('title') }}" required autocomplete="off">
            </div>

            <div class="field-group">
                <label for="author" class="field-label">Author <span style="color: #dc2626;">*</span></label>
                <input type="text" name="author" id="author" class="input-style" value="{{ old('author') }}" required autocomplete="off">
            </div>

            <div class="field-group">
                <label for="isbn" class="field-label">ISBN</label>
                <input type="text" name="isbn" id="isbn" class="input-style" value="{{ old('isbn') }}" placeholder="e.g., 978-3-16-148410-0">
            </div>

            <div class="field-group">
                <label for="category_id" class="field-label">Category <span style="color: #dc2626;">*</span></label>
                <select name="category_id" id="category_id" class="input-style" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field-group">
                <label for="publisher" class="field-label">Publisher</label>
                <input type="text" name="publisher" id="publisher" class="input-style" value="{{ old('publisher') }}">
            </div>

            <div class="field-group">
                <label for="published_year" class="field-label">Published Year</label>
                <input type="number" name="published_year" id="published_year" class="input-style" min="1000" max="{{ date('Y') }}" value="{{ old('published_year') }}">
            </div>

            <div class="field-group">
                <label for="total_copies" class="field-label">Total Copies <span style="color: #dc2626;">*</span></label>
                <input type="number" name="total_copies" id="total_copies" class="input-style" min="1" value="{{ old('total_copies', 1) }}" required>
            </div>

            <div class="field-group">
                <label for="status" class="field-label">Status <span style="color: #dc2626;">*</span></label>
                <select name="status" id="status" class="input-style" required>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <div class="field-group full-width">
                <label for="cover_image" class="field-label">Book Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" class="input-style" accept="image/*" style="padding: 5px;">
                <span style="font-size: 12px; color: #6b7280;">Accepted formats: jpeg, png, jpg, webp (Max size: 2MB)</span>
            </div>

            <div class="field-group full-width">
                <label for="description" class="field-label">Description</label>
                <textarea name="description" id="description" class="input-style" rows="4" style="resize: vertical;">{{ old('description') }}</textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <a href="{{ route('books.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Save Book</button>
        </div>
    </form>
</div>
@endsection