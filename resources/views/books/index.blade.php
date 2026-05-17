@extends('layouts.app')
@section('title', 'Books')
@section('page-title', 'Book Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book"></i> All Books</h3>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Book
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('books.index') }}" class="filter-form">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search title, author, ISBN..."
                class="form-control search-input"
            >
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>ISBN</th>
                    <th>Copies</th>
                    <th>Available</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $book)
                    <tr>
                        <td>{{ $books->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="text-primary font-semibold">
                                {{ $book->title }}
                            </a>
                            @if($book->published_year)
                                <br><small class="text-muted">{{ $book->published_year }}</small>
                            @endif
                        </td>
                        <td>{{ $book->author }}</td>
                        <td><span class="badge badge-info">{{ $book->category->name }}</span></td>
                        <td class="text-mono">{{ $book->isbn ?? '—' }}</td>
                        <td class="text-center">{{ $book->total_copies }}</td>
                        <td class="text-center">
                            <span class="{{ $book->available_copies > 0 ? 'text-success font-bold' : 'text-danger font-bold' }}">
                                {{ $book->available_copies }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $book->isAvailable() ? 'success' : 'danger' }}">
                                {{ $book->isAvailable() ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this book?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-book fa-2x mb-2"></i><br>
                            No books found. <a href="{{ route('books.create') }}">Add the first book</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }} books
        </div>
        {{ $books->links() }}
    </div>
</div>
@endsection