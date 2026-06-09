@extends('layouts.app')
@section('title', __('menu.popular_title'))
@section('page-title', __('menu.popular_title'))

@push('styles')
<style>
@media (max-width: 480px) {
    .table-popular th:nth-child(3), .table-popular td:nth-child(3),
    .table-popular th:nth-child(4), .table-popular td:nth-child(4),
    .table-popular th:nth-child(5), .table-popular td:nth-child(5),
    .table-popular th:nth-child(6), .table-popular td:nth-child(6) { display: none; }
}
.rank-badge {
    display:inline-flex; align-items:center; justify-content:center;
    width:32px; height:32px; border-radius:50%; font-weight:700; font-size:.9rem;
}
.rank-1 { background:var(--warning-light); color:var(--warning); border:2px solid var(--accent); }
.rank-2 { background:var(--bg); color:var(--text-muted); border:2px solid var(--border); }
.rank-3 { background:var(--danger-light); color:var(--danger); border:2px solid #f0bebe; }
.rank-other { background:var(--bg); color:var(--text-muted); border:1px solid var(--border); font-size:.8rem; }
.borrow-bar-bg { background:var(--border); border-radius:4px; height:8px; }
.borrow-bar-fill { background:linear-gradient(90deg,var(--primary),var(--accent)); border-radius:4px; height:8px; transition:width .4s; }
.fire-icon { color:var(--accent); }
</style>
@endpush

@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> {{ __('menu.back_to_reports') }}
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-fire fire-icon"></i> {{ __('menu.most_borrowed') }}</h3>
        <span style="font-size:.85rem;color:var(--text-muted)">{{ __('menu.books_ranked', ['count' => $books->total()]) }}</span>
    </div>

    @php $maxBorrows = $books->first()->borrows_count ?? 1; @endphp

    <div class="card-body p-0 table-wrap table-popular">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width:60px">{{ __('menu.rank') }}</th>
                    <th>{{ __('menu.book_title') }}</th>
                    <th>{{ __('menu.book_author') }}</th>
                    <th>{{ __('menu.book_category') }}</th>
                    <th>{{ __('menu.copies') }}</th>
                    <th>{{ __('menu.times_borrowed') }}</th>
                    <th>{{ __('menu.popularity') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $i => $book)
                @php $rank = ($books->currentPage() - 1) * $books->perPage() + $i + 1; @endphp
                <tr>
                    <td>
                        @if($rank === 1)
                            <span class="rank-badge rank-1"><i class="fas fa-trophy"></i></span>
                        @elseif($rank === 2)
                            <span class="rank-badge rank-2">2</span>
                        @elseif($rank === 3)
                            <span class="rank-badge rank-3">3</span>
                        @else
                            <span class="rank-badge rank-other">#{{ $rank }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('books.show', $book) }}" class="text-primary fw-600">
                            {{ $book->title }}
                        </a>
                        @if($book->isbn)
                            <br><small class="text-muted text-mono">{{ __('menu.book_isbn') }}: {{ $book->isbn }}</small>
                        @endif
                    </td>
                    <td>{{ $book->author ?? '—' }}</td>
                    <td>{{ $book->category->name ?? '—' }}</td>
                    <td>
                        {{ $book->available_copies }}/{{ $book->total_copies }}
                        <br><small class="text-muted">{{ __('menu.available') }}</small>
                    </td>
                    <td>
                        <strong style="font-size:1.1rem">{{ number_format($book->borrows_count) }}</strong>
                        <small class="text-muted"> {{ __('menu.times') }}</small>
                    </td>
                    <td style="width:160px">
                        <div class="borrow-bar-bg">
                            <div class="borrow-bar-fill"
                                 style="width:{{ $maxBorrows > 0 ? min(100, ($book->borrows_count / $maxBorrows) * 100) : 0 }}%">
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted" style="padding:40px">
                        <i class="fas fa-book" style="font-size:2rem;opacity:.2;display:block;margin-bottom:8px"></i>
                        {{ __('menu.no_popular_data') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            {{ __('menu.showing_results', ['from' => $books->firstItem() ?? 0, 'to' => $books->lastItem() ?? 0, 'total' => $books->total()]) }}
        </div>
        {{ $books->links() }}
    </div>
</div>
@endsection
