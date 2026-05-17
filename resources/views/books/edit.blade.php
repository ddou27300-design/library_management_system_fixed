@extends('layouts.app')
@section('title', 'Edit Book')
@section('page-title', 'Edit Book')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Edit: {{ $book->title }}</h3>
        <div>
            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-row">
                <div class="form-group col-8">
                    <label for="title">Book Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $book->title) }}" required>
                    @error('title')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-4">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                           value="{{ old('isbn', $book->isbn) }}">
                    @error('isbn')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="author">Author <span class="required">*</span></label>
                    <input type="text" id="author" name="author" class="form-control"
                           value="{{ old('author', $book->author) }}" required>
                </div>
                <div class="form-group col-6">
                    <label for="publisher">Publisher</label>
                    <input type="text" id="publisher" name="publisher" class="form-control"
                           value="{{ old('publisher', $book->publisher) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="category_id">Category <span class="required">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="published_year">Published Year</label>
                    <input type="number" id="published_year" name="published_year" class="form-control"
                           value="{{ old('published_year', $book->published_year) }}" min="1000" max="{{ date('Y') }}">
                </div>
                <div class="form-group col-4">
                    <label for="total_copies">
                        Total Copies <span class="required">*</span>
                        <small class="text-muted">({{ $book->borrowed_copies }} borrowed)</small>
                    </label>
                    <input type="number" id="total_copies" name="total_copies" class="form-control"
                           value="{{ old('total_copies', $book->total_copies) }}"
                           min="{{ $book->borrowed_copies }}" required>
                    @error('total_copies')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-8">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
                </div>
                <div class="form-group col-4">
                    <label>Current Cover</label>
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover"
                             style="max-height:100px;border-radius:4px;display:block;margin-bottom:8px;">
                    @else
                        <div class="no-cover">No cover image</div>
                    @endif
                    <label for="cover_image">Replace Image</label>
                    <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                    <label for="status" class="mt-3">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status', $book->status) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Book
                </button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection