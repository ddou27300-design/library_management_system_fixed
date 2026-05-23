@extends('layouts.app')
@section('title', __('menu.all_books'))
@section('page-title', __('menu.book_management'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book"></i> {{ __('menu.all_books') }}</h3>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('menu.add_book') }}
        </a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('books.index') }}" class="filter-form">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="{{ __('menu.search_books') }}"
                class="form-control search-input"
            >
            <select name="category_id" class="form-control">
                <option value="">{{ __('menu.all_categories') }}</option>
                @isset($categories)
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                @endisset
            </select>
            <select name="status" class="form-control">
                <option value="">{{ __('menu.all_status') }}</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>{{ __('menu.available') }}</option>
                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>{{ __('menu.unavailable') }}</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {{ __('menu.filter') }}</button>
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('menu.reset') }}</a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 80px;">{{ __('menu.cover_image') }}</th>
                    <th>{{ __('menu.book_title') }}</th>
                    <th>{{ __('menu.book_author') }}</th>
                    <th>{{ __('menu.book_category') }}</th>
                    <th>{{ __('menu.book_isbn') }}</th>
                    <th class="text-center">{{ __('menu.book_copies') }}</th>
                    <th class="text-center">{{ __('menu.book_available') }}</th>
                    <th>{{ __('menu.book_status') }}</th>
                    <th class="text-end" style="padding-right: 20px;">{{ __('menu.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        
                        <td>
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                     alt="{{ $book->title }}" 
                                     style="width: 45px; height: 60px; object-fit: cover; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.15);">
                            @else
                                <div style="width: 45px; height: 60px; background-color: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #9ca3af; font-size: 9px; font-weight: bold; line-height: 1;">
                                    <span>{{ __('menu.no_cover') }}</span>
                                </div>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('books.show', $book->id) }}" class="text-primary font-semibold text-decoration-none">
                                {{ $book->title }}
                            </a>
                            @if($book->published_year)
                                <br><small class="text-muted">{{ $book->published_year }}</small>
                            @endif
                        </td>
                        <td>{{ $book->author }}</td>
                        
                        {{-- ផ្នែកកែសម្រួល៖ បន្ថែមការការពារ Null-safety ទៅលើ Category --}}
                        <td>
                            @if($book->category)
                                <span class="badge bg-info text-dark">{{ $book->category->name }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('menu.uncategorized') }}</span>
                            @endif
                        </td>

                        <td class="text-mono">{{ $book->isbn ?? '—' }}</td>
                        <td class="text-center">{{ $book->total_copies }}</td>
                        <td class="text-center">
                            <span class="{{ ($book->available_copies ?? 0) > 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ $book->available_copies ?? 0 }}
                            </span>
                        </td>
                        <td>
                            {{-- ការពារករណីមិនមាន method isAvailable() ក្នុង Model ដោយប្រើលក្ខខណ្ឌជំនួស --}}
                            @php
                                $isAvailable = method_exists($book, 'isAvailable') ? $book->isAvailable() : (($book->status === 'available') && ($book->available_copies > 0));
                            @endphp
                            <span class="badge bg-{{ $isAvailable ? 'success' : 'danger' }}">
                                {{ $isAvailable ? __('menu.available') : __('menu.unavailable') }}
                            </span>
                        </td>
                        <td class="text-end" style="padding-right: 20px;">
                            <div class="action-buttons d-flex gap-1 justify-content-end">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('menu.delete_book_confirm') }}')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fas fa-book fa-3x mb-3 text-secondary"></i><br>
                            <h5>{{ __('menu.no_books_found') }}</h5>
                            <p class="text-sm">{{ __('menu.no_books_filter_tip') }} <a href="{{ route('books.create') }}" class="text-primary">{{ __('menu.add_new_book_record') }}</a>.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="pagination-info text-muted small">
            {{ __('menu.showing_results', [
                'from' => method_exists($books, 'firstItem') ? ($books->firstItem() ?? 0) : 1,
                'to' => method_exists($books, 'lastItem') ? ($books->lastItem() ?? 0) : 1,
                'total' => method_exists($books, 'total') ? $books->total() : count($books)
            ]) }}
        </div>
        <div>
            {{ method_exists($books, 'links') ? $books->links() : '' }}
        </div>
    </div>
</div>
@endsection