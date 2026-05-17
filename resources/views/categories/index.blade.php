@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', __('menu.categories'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-tags"></i> {{ __('menu.categories') }}</h3>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('menu.add_new') }}
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('menu.book_category') }}</th>
                    <th>Description</th>
                    <th>Books</th>
                    <th>{{ __('menu.book_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-600">{{ $category->name }}</span>
                            <br><small class="text-muted">{{ $category->slug }}</small>
                        </td>
                        <td class="text-muted">{{ $category->description ?? '—' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $category->books_count }} books</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> {{ __('menu.edit') }}
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding:40px">
                            <i class="fas fa-tags" style="font-size:2rem;opacity:.3;display:block;margin-bottom:10px"></i>
                            No categories yet. <a href="{{ route('categories.create') }}">Add the first one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            Showing {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }}
            of {{ $categories->total() }} categories
        </div>
        {{ $categories->links() }}
    </div>
</div>
@endsection
