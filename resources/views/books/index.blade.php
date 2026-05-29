@extends('layouts.app')
@section('title', __('menu.all_books'))
@section('page-title', __('menu.book_management'))

@php
$facultyIcons = [
    __('menu.faculty_college_science_technology')         => 'fas fa-microchip',
    __('menu.faculty_college_arts_humanities_languages')  => 'fas fa-palette',
    __('menu.faculty_college_business_tourism')           => 'fas fa-chart-line',
    __('menu.faculty_college_humanities_community_development') => 'fas fa-hand-holding-heart',
    __('menu.faculty_college_agriculture_food_processing') => 'fas fa-seedling',
];
$facultyShort = [
    __('menu.faculty_college_science_technology')         => __('menu.faculty_short_science'),
    __('menu.faculty_college_arts_humanities_languages')  => __('menu.faculty_short_arts'),
    __('menu.faculty_college_business_tourism')           => __('menu.faculty_short_business'),
    __('menu.faculty_college_humanities_community_development') => __('menu.faculty_short_community'),
    __('menu.faculty_college_agriculture_food_processing') => __('menu.faculty_short_agriculture'),
];
@endphp

@section('content')
<style>
.books-table { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.books-table table { position: relative; }
.faculty-scroll {
    overflow-x: auto; -webkit-overflow-scrolling: touch;
    padding: 18px 22px 14px; background: var(--bg);
    border-bottom: 1px solid var(--border);
}
.faculty-scroll::-webkit-scrollbar { height: 3px; }
.faculty-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
.faculty-tabs {
    display: flex; flex-wrap: wrap; gap: 10px; padding-bottom: 2px;
}
.faculty-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 100px;
    font-size: .82rem; font-weight: 600; color: var(--text-muted);
    background: var(--surface); border: 1.5px solid var(--border);
    text-decoration: none; transition: all var(--transition);
    white-space: nowrap; cursor: pointer; flex: 1 1 auto; min-width: 0;
}
.faculty-tab i { font-size: .85rem; opacity: .7; transition: opacity var(--transition); }
.faculty-tab .tab-full { display: inline; }
.faculty-tab .tab-short { display: none; }
.faculty-tab:hover {
    color: var(--primary); border-color: var(--primary-light);
    background: var(--primary-soft); transform: translateY(-1px);
    box-shadow: 0 3px 12px rgba(26,60,94,.1);
}
.faculty-tab.active {
    color: #fff; background: var(--primary);
    border-color: var(--primary); box-shadow: 0 4px 14px rgba(26,60,94,.25);
}
.faculty-tab.active i { opacity: 1; }
.faculty-tab:hover i { opacity: 1; }
.filter-toggle { display: none; }
.filter-collapse { display: contents; }
@media (max-width: 900px) {
    .faculty-tab .tab-full { display: none; }
    .faculty-tab .tab-short { display: inline; }
}
@media (max-width: 480px) {
    .faculty-scroll { padding: 12px 16px 10px; overflow-x: auto; }
    .faculty-tabs { width: 100%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
    .faculty-tab {
        padding: 8px 10px; font-size: .72rem; gap: 4px;
        justify-content: center; border-radius: 8px; white-space: nowrap;
    }
    .faculty-tab i { font-size: .75rem; }
    .faculty-tab i { font-size: .78rem; }
    .filter-bar { padding: 10px 14px; }
    .filter-form { gap: 8px; }
    .filter-form .search-input { order: -1; }
    .filter-toggle {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px; font-size: .8rem; font-weight: 600;
        background: var(--bg); border: 1.5px solid var(--border); cursor: pointer;
        color: var(--text-muted); transition: all var(--transition);
    }
    .filter-toggle:hover { color: var(--primary); border-color: var(--primary-light); background: var(--primary-soft); }
    .filter-toggle.active { color: var(--primary); border-color: var(--primary); background: var(--primary-soft); }
    .filter-collapse { display: none; width: 100%; flex-direction: column; gap: 8px; }
    .filter-collapse.open { display: flex; }
    .filter-form .btn { font-size: .8rem; padding: 8px 14px; }
    .filter-form .btn span { display: inline; }
    .filter-form select { font-size: .8rem; }
    .books-table th:nth-child(1), .books-table td:nth-child(1),
    .books-table th:nth-child(2), .books-table td:nth-child(2),
    .books-table th:nth-child(4), .books-table td:nth-child(4),
    .books-table th:nth-child(6), .books-table td:nth-child(6),
    .books-table th:nth-child(7), .books-table td:nth-child(7),
    .books-table th:nth-child(8), .books-table td:nth-child(8),
    .books-table th:nth-child(9), .books-table td:nth-child(9) { display: none; }
    .books-table th:nth-child(3), .books-table td:nth-child(3) { min-width: 140px; }
    .books-table th:nth-child(10), .books-table td:nth-child(10) { min-width: 80px; }
    .books-table td:last-child { position: sticky; right: 0; background: var(--surface); z-index: 2; box-shadow: -2px 0 4px rgba(0,0,0,0.05); }
    .action-buttons .btn { min-height: 40px; min-width: 40px; padding: 6px 10px; font-size: .85rem; }
    .action-buttons { gap: 4px; }
}
@media (max-width: 360px) {
    .books-table th:nth-child(5), .books-table td:nth-child(5) { display: none; }
}
</style>
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book"></i> {{ __('menu.all_books') }}</h3>
        @can('admin')
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('menu.add_book') }}
        </a>
        @endcan
    </div>

    {{-- Faculty Tabs --}}
    <div class="faculty-scroll">
        <div class="faculty-tabs">
            <a href="{{ route('books.index', array_merge(request()->query(), ['faculty' => '', 'page' => null])) }}"
               class="faculty-tab {{ !request('faculty') ? 'active' : '' }}">
                <i class="fas fa-globe"></i>
                <span><span class="tab-full">{{ __('menu.all_faculties') }}</span><span class="tab-short">{{ __('menu.all_short') }}</span></span>
            </a>
            @isset($faculties)
                @foreach($faculties as $fac)
                    <a href="{{ route('books.index', array_merge(request()->query(), ['faculty' => $fac, 'page' => null])) }}"
                       class="faculty-tab {{ request('faculty') == $fac ? 'active' : '' }}">
                        <i class="{{ $facultyIcons[$fac] ?? 'fas fa-building' }}"></i>
                        <span><span class="tab-full">{{ $fac }}</span><span class="tab-short">{{ $facultyShort[$fac] ?? $fac }}</span></span>
                    </a>
                @endforeach
            @endisset
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('books.index') }}" class="filter-form">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="{{ __('menu.search_books') }}" class="form-control search-input">

            <div class="filter-collapse" id="filterCollapse">
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
                <select name="faculty" class="form-control">
                    <option value="">{{ __('menu.all_faculties') }}</option>
                    @isset($faculties)
                        @foreach($faculties as $fac)
                            <option value="{{ $fac }}" {{ request('faculty') == $fac ? 'selected' : '' }}>
                                {{ $fac }}
                            </option>
                        @endforeach
                    @endisset
                </select>
                <select name="status" class="form-control">
                    <option value="">{{ __('menu.all_status') }}</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>{{ __('menu.available') }}</option>
                    <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>{{ __('menu.unavailable') }}</option>
                </select>
                <div style="display:flex;gap:8px;width:100%;">
                    <button type="submit" class="btn btn-primary" style="flex:1;"><i class="fas fa-search"></i> <span>{{ __('menu.filter') }}</span></button>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('menu.reset') }}</a>
                </div>
            </div>

            <button type="button" class="filter-toggle" id="filterToggle" onclick="toggleFilter()">
                <i class="fas fa-sliders-h"></i> <span>{{ __('menu.more_filters') }}</span>
            </button>
        </form>
    </div>

    <div class="card-body p-0 table-wrap books-table">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('menu.cover_image') }}</th>
                    <th>{{ __('menu.book_title') }}</th>
                    <th>{{ __('menu.book_author') }}</th>
                    <th>{{ __('menu.book_category') }}</th>
                    <th>{{ __('menu.faculty') }}</th>
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
                                <span class="badge badge-info">{{ $book->category->name }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('menu.uncategorized') }}</span>
                            @endif
                        </td>

                        <td>{{ $book->faculty ?? '—' }}</td>

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
                            <span class="badge badge-{{ $isAvailable ? 'success' : 'danger' }}">
                                {{ $isAvailable ? __('menu.available') : __('menu.unavailable') }}
                            </span>
                        </td>
                        <td class="text-end" style="padding-right: 20px;">
                            <div class="action-buttons d-flex gap-1 justify-content-end">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('admin')
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning" title="Edit"
                                   data-confirm="{{ json_encode(['message' => __('menu.edit_book_confirm'), 'icon' => 'warning', 'accent' => true]) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline"
                                      data-confirm="{{ json_encode(['message' => __('menu.delete_book_confirm')]) }}">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fas fa-book fa-3x mb-3 text-secondary"></i><br>
                            <h5>{{ __('menu.no_books_found') }}</h5>
                            <p class="text-sm">{{ __('menu.no_books_filter_tip') }} @can('admin')<a href="{{ route('books.create') }}" class="text-primary">{{ __('menu.add_new_book_record') }}</a>@endcan.</p>
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

<script>
function toggleFilter() {
    var el = document.getElementById('filterCollapse');
    var btn = document.getElementById('filterToggle');
    el.classList.toggle('open');
    btn.classList.toggle('active');
    btn.querySelector('span').textContent = el.classList.contains('open')
        ? '{{ __("menu.filter") }}'
        : '{{ __("menu.more_filters") }}';
}
if (window.innerWidth <= 480) {
    var searchVal = document.querySelector('.filter-form .search-input');
    if (searchVal && searchVal.value.trim() !== '') {
        document.getElementById('filterCollapse').classList.add('open');
        document.getElementById('filterToggle').classList.add('active');
        document.getElementById('filterToggle').querySelector('span').textContent = '{{ __("menu.filter") }}';
    }
}
</script>
@endsection