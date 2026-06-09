@extends('layouts.app')
@section('title', __('menu.edit_book'))
@section('page-title', __('menu.edit_book'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> {{ __('menu.edit_book') }}: {{ $book->title }}</h3>
        <div>
            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-info btn-sm">
                <i class="fas fa-eye"></i> {{ __('menu.view') }}
            </a>
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="title">{{ __('menu.book_title') }} <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $book->title) }}" required>
                    @error('title')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="isbn">{{ __('menu.book_isbn') }}</label>
                    <input type="text" id="isbn" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                           value="{{ old('isbn', $book->isbn) }}">
                    @error('isbn')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="author">{{ __('menu.book_author') }} <span class="required">*</span></label>
                    <input type="text" id="author" name="author" class="form-control"
                           value="{{ old('author', $book->author) }}" required>
                </div>
                <div class="form-group">
                    <label for="publisher">{{ __('menu.book_publisher') }}</label>
                    <input type="text" id="publisher" name="publisher" class="form-control"
                           value="{{ old('publisher', $book->publisher) }}">
                </div>
            </div>

            <div class="form-row form-row-4">
                <div class="form-group">
                    <label for="category_id">{{ __('menu.book_category') }} <span class="required">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="faculty">{{ __('menu.faculty') }}</label>
                    <select name="faculty" id="faculty" class="form-control">
                        <option value="">{{ __('menu.select_faculty') }}</option>
                        <option value="{{ __('menu.faculty_college_science_technology') }}" {{ old('faculty', $book->faculty) == __('menu.faculty_college_science_technology') ? 'selected' : '' }}>{{ __('menu.faculty_college_science_technology') }}</option>
                        <option value="{{ __('menu.faculty_college_arts_humanities_languages') }}" {{ old('faculty', $book->faculty) == __('menu.faculty_college_arts_humanities_languages') ? 'selected' : '' }}>{{ __('menu.faculty_college_arts_humanities_languages') }}</option>
                        <option value="{{ __('menu.faculty_college_business_tourism') }}" {{ old('faculty', $book->faculty) == __('menu.faculty_college_business_tourism') ? 'selected' : '' }}>{{ __('menu.faculty_college_business_tourism') }}</option>
                        <option value="{{ __('menu.faculty_college_humanities_community_development') }}" {{ old('faculty', $book->faculty) == __('menu.faculty_college_humanities_community_development') ? 'selected' : '' }}>{{ __('menu.faculty_college_humanities_community_development') }}</option>
                        <option value="{{ __('menu.faculty_college_agriculture_food_processing') }}" {{ old('faculty', $book->faculty) == __('menu.faculty_college_agriculture_food_processing') ? 'selected' : '' }}>{{ __('menu.faculty_college_agriculture_food_processing') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="published_year">{{ __('menu.book_published_year') }}</label>
                    <input type="number" id="published_year" name="published_year" class="form-control"
                           value="{{ old('published_year', $book->published_year) }}" min="1000" max="{{ date('Y') }}">
                </div>
                <div class="form-group">
                    <label for="total_copies">
                        {{ __('menu.book_total_copies') }} <span class="required">*</span>
                        <small class="text-muted">({{ __('menu.borrowed_copies', ['count' => $book->borrowed_copies]) }})</small>
                    </label>
                    <input type="number" id="total_copies" name="total_copies" class="form-control"
                           value="{{ old('total_copies', $book->total_copies) }}"
                           min="{{ $book->borrowed_copies }}" required>
                    @error('total_copies')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="description">{{ __('menu.description') }}</label>
                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label>{{ __('menu.current_cover') }}</label>
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover"
                             style="max-height:100px;border-radius:var(--radius-sm);display:block;margin-bottom:8px;">
                    @else
                        <div class="no-cover">{{ __('menu.no_cover_image') }}</div>
                    @endif
                    <label for="cover_image">{{ __('menu.replace_image') }}</label>
                    <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                    <label for="status" class="mt-3">{{ __('menu.status') }}</label>
                    <select id="status" name="status" class="form-control">
                        <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>{{ __('menu.available') }}</option>
                        <option value="unavailable" {{ old('status', $book->status) === 'unavailable' ? 'selected' : '' }}>{{ __('menu.unavailable') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('menu.update_book') }}
                </button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection