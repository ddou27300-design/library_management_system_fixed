@extends('layouts.app')
@section('title', __('menu.categories'))
@section('page-title', __('menu.categories'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-tags"></i> {{ __('menu.categories') }}</h3>
        @can('admin')
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('menu.add_new') }}
        </a>
        @endcan
    </div>

    <div class="card-body p-0 table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('menu.book_category') }}</th>
                    <th>{{ __('menu.description') }}</th>
                    <th>{{ __('menu.books') }}</th>
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
                            <span class="badge badge-info">{{ __('menu.books_count', ['count' => $category->books_count]) }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                @can('admin')
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary"
                                   data-confirm="{{ json_encode(['message' => __('menu.edit_category_confirm'), 'icon' => 'warning', 'accent' => true]) }}">
                                    <i class="fas fa-edit"></i> {{ __('menu.edit') }}
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline"
                                      data-confirm="{{ json_encode(['message' => __('menu.delete_record_confirm')]) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding:40px">
                            <i class="fas fa-tags" style="font-size:2rem;opacity:.3;display:block;margin-bottom:10px"></i>
                            {{ __('menu.no_categories') }} @can('admin')<a href="{{ route('categories.create') }}">{{ __('menu.add_first_category') }}</a>@endcan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            {{ __('menu.showing_results', ['from' => $categories->firstItem() ?? 0, 'to' => $categories->lastItem() ?? 0, 'total' => $categories->total()]) }}
        </div>
        {{ $categories->links() }}
    </div>
</div>
@endsection
