@extends('layouts.app')
@section('title', $book->title)
@section('page-title', __('menu.book_detail'))

@section('content')
<div class="row-2col" style="align-items:flex-start">
    <!-- Book Info Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-book"></i> {{ __('menu.book_info') }}</h3>
            <div>
                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> {{ __('menu.edit') }}
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="book-detail">
                <div class="book-cover-lg">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                    @else
                        <div class="no-cover-lg"><i class="fas fa-book fa-4x"></i></div>
                    @endif
                </div>
                <div class="book-meta">
                    <h2>{{ $book->title }}</h2>
                    <p class="book-author">{{ __('menu.by_author') }} <strong>{{ $book->author }}</strong></p>

                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_isbn') }}</span>
                            <span class="meta-value text-mono">{{ $book->isbn ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_category') }}</span>
                            <span class="badge badge-info">{{ $book->category->name }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_publisher') }}</span>
                            <span class="meta-value">{{ $book->publisher ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_published_year') }}</span>
                            <span class="meta-value">{{ $book->published_year ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_total_copies') }}</span>
                            <span class="meta-value font-bold">{{ $book->total_copies }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_available') }}</span>
                            <span class="meta-value font-bold {{ $book->available_copies > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $book->available_copies }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">{{ __('menu.book_status') }}</span>
                            <span class="badge badge-{{ $book->isAvailable() ? 'success' : 'danger' }}">
                                {{ $book->isAvailable() ? __('menu.available') : __('menu.unavailable') }}
                            </span>
                        </div>
                    </div>

                    @if($book->description)
                        <div class="mt-3">
                            <strong>{{ __('menu.description_label') }}</strong>
                            <p class="text-muted mt-1">{{ $book->description }}</p>
                        </div>
                    @endif

                    @if($book->isAvailable())
                        <div class="mt-4">
                            <a href="{{ route('borrow.create') }}?book_id={{ $book->id }}" class="btn btn-primary">
                                <i class="fas fa-hand-holding"></i> {{ __('menu.issue_this_book') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow History -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> {{ __('menu.borrow_history') }}</h3>
            <span class="badge badge-info">{{ $borrowHistory->total() }} {{ __('menu.records') }}</span>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('menu.student') }}</th>
                        <th>{{ __('menu.borrow_date') }}</th>
                        <th>{{ __('menu.due_date') }}</th>
                        <th>{{ __('menu.return_date') }}</th>
                        <th>{{ __('menu.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowHistory as $borrow)
                        <tr>
                            <td>{{ $borrow->student->name ?? '—' }}</td>
                            <td>{{ $borrow->borrow_date->format('d/m/Y') }}</td>
                            <td>{{ $borrow->due_date->format('d/m/Y') }}</td>
                            <td>{{ $borrow->return_date ? $borrow->return_date->format('d/m/Y') : '—' }}</td>
                            <td><span class="badge badge-{{ $borrow->status }}">{{ __('menu.status_' . $borrow->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">{{ __('menu.never_borrowed') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($borrowHistory->hasPages())
            <div class="card-footer">{{ $borrowHistory->links() }}</div>
        @endif
    </div>
</div>
@endsection