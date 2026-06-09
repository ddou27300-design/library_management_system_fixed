@extends('layouts.app')
@section('title', __('menu.add_new_book_title'))
@section('page-title', __('menu.add_new_book_title'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-book"></i> {{ __('menu.add_new_book_title') }}</h3>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back_to_list') }}
        </a>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-error mb-3">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin:0;padding-left:16px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="title">{{ __('menu.book_title') }} <span class="required">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="author">{{ __('menu.book_author') }} <span class="required">*</span></label>
                    <input type="text" name="author" id="author" class="form-control" value="{{ old('author') }}" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="isbn">{{ __('menu.book_isbn') }}</label>
                    <input type="text" name="isbn" id="isbn" class="form-control" value="{{ old('isbn') }}" placeholder="e.g., 978-3-16-148410-0">
                </div>

                <div class="form-group">
                    <label for="category_id">{{ __('menu.book_category') }} <span class="required">*</span></label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">{{ __('menu.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="faculty">{{ __('menu.faculty') }}</label>
                    <select name="faculty" id="faculty" class="form-control">
                        <option value="">{{ __('menu.select_faculty') }}</option>
                        <option value="{{ __('menu.faculty_college_science_technology') }}" {{ old('faculty') == __('menu.faculty_college_science_technology') ? 'selected' : '' }}>{{ __('menu.faculty_college_science_technology') }}</option>
                        <option value="{{ __('menu.faculty_college_arts_humanities_languages') }}" {{ old('faculty') == __('menu.faculty_college_arts_humanities_languages') ? 'selected' : '' }}>{{ __('menu.faculty_college_arts_humanities_languages') }}</option>
                        <option value="{{ __('menu.faculty_college_business_tourism') }}" {{ old('faculty') == __('menu.faculty_college_business_tourism') ? 'selected' : '' }}>{{ __('menu.faculty_college_business_tourism') }}</option>
                        <option value="{{ __('menu.faculty_college_humanities_community_development') }}" {{ old('faculty') == __('menu.faculty_college_humanities_community_development') ? 'selected' : '' }}>{{ __('menu.faculty_college_humanities_community_development') }}</option>
                        <option value="{{ __('menu.faculty_college_agriculture_food_processing') }}" {{ old('faculty') == __('menu.faculty_college_agriculture_food_processing') ? 'selected' : '' }}>{{ __('menu.faculty_college_agriculture_food_processing') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="publisher">{{ __('menu.book_publisher') }}</label>
                    <input type="text" name="publisher" id="publisher" class="form-control" value="{{ old('publisher') }}">
                </div>

                <div class="form-group">
                    <label for="published_year">{{ __('menu.book_published_year') }}</label>
                    <input type="number" name="published_year" id="published_year" class="form-control" min="1000" max="{{ date('Y') }}" value="{{ old('published_year') }}">
                </div>

                <div class="form-group">
                    <label for="total_copies">{{ __('menu.book_total_copies') }} <span class="required">*</span></label>
                    <input type="number" name="total_copies" id="total_copies" class="form-control" min="1" value="{{ old('total_copies', 1) }}" required>
                </div>

                <div class="form-group">
                    <label for="status">{{ __('menu.book_status') }} <span class="required">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>{{ __('menu.available') }}</option>
                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>{{ __('menu.unavailable') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cover_image">{{ __('menu.cover_image') }}</label>
                    <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*" style="padding: 5px;">
                    <span class="form-hint">{{ __('menu.cover_image_hint') }}</span>
                </div>

                <div class="form-group" style="grid-column:span 2">
                    <label for="description">{{ __('menu.description') }}</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('menu.save_book') }}
                </button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection