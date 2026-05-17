@extends('layouts.app')
@section('title', $book->title)
@section('page-title', 'Book Detail')

@section('content')
<div class="row-2col" style="align-items:flex-start">
    <!-- Book Info Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-book"></i> Book Information</h3>
            <div>
                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
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
                    <p class="book-author">by <strong>{{ $book->author }}</strong></p>

                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">ISBN</span>
                            <span class="meta-value text-mono">{{ $book->isbn ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Category</span>
                            <span class="badge badge-info">{{ $book->category->name }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Publisher</span>
                            <span class="meta-value">{{ $book->publisher ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Year</span>
                            <span class="meta-value">{{ $book->published_year ?? '—' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Copies</span>
                            <span class="meta-value font-bold">{{ $book->total_copies }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Available</span>
                            <span class="meta-value font-bold {{ $book->available_copies > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $book->available_copies }}
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="badge badge-{{ $book->isAvailable() ? 'success' : 'danger' }}">
                                {{ $book->isAvailable() ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    </div>

                    @if($book->description)
                        <div class="mt-3">
                            <strong>Description:</strong>
                            <p class="text-muted mt-1">{{ $book->description }}</p>
                        </div>
                    @endif

                    @if($book->isAvailable())
                        <div class="mt-4">
                            <a href="{{ route('borrow.create') }}?book_id={{ $book->id }}" class="btn btn-primary">
                                <i class="fas fa-hand-holding"></i> Issue This Book
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
            <h3><i class="fas fa-history"></i> Borrow History</h3>
            <span class="badge badge-info">{{ $borrowHistory->total() }} records</span>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Borrowed</th>
                        <th>Due</th>
                        <th>Returned</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowHistory as $borrow)
                        <tr>
                            <td>{{ $borrow->student->name ?? '—' }}</td>
                            <td>{{ $borrow->borrow_date->format('d/m/Y') }}</td>
                            <td>{{ $borrow->due_date->format('d/m/Y') }}</td>
                            <td>{{ $borrow->return_date ? $borrow->return_date->format('d/m/Y') : '—' }}</td>
                            <td><span class="badge badge-{{ $borrow->status }}">{{ ucfirst($borrow->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Never borrowed yet.</td></tr>
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