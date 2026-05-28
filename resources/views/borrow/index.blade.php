@extends('layouts.app')

@section('title', __('menu.borrow_history'))
@section('page-title', __('menu.borrow_records'))

@section('content')
<style>
@media (max-width: 480px) {
    .borrows-table th:nth-child(1), .borrows-table td:nth-child(1),
    .borrows-table th:nth-child(4), .borrows-table td:nth-child(4),
    .borrows-table th:nth-child(5), .borrows-table td:nth-child(5),
    .borrows-table th:nth-child(6), .borrows-table td:nth-child(6) { display: none; }
}
</style>
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> {{ __('menu.borrow_history') }}</h3>
        @can('admin')
        <a href="{{ route('borrows.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('menu.issue_new_book') }}
        </a>
        @endcan
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('borrows.index') }}" class="filter-form">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="{{ __('menu.searching_borrows') }}" 
                class="form-control search-input"
            >
            <select name="status" class="form-control">
                <option value="">{{ __('menu.all_status_borrow') }}</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>{{ __('menu.status_borrowed') }}</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>{{ __('menu.status_returned') }}</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('menu.status_overdue') }}</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {{ __('menu.filter') }}</button>
            <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">{{ __('menu.reset') }}</a>
        </form>
    </div>

    <div class="card-body p-0 table-wrap borrows-table">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>{{ __('menu.student') }}</th>
                    <th>{{ __('menu.book_title') }}</th>
                    <th>{{ __('menu.borrow_date') }}</th>
                    <th>{{ __('menu.due_date') }}</th>
                    <th>{{ __('menu.return_date') }}</th>
                    <th>{{ __('menu.status') }}</th>
                    <th class="text-end" style="padding-right: 20px;">{{ __('menu.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $index => $borrow)
                    <tr>
                        <td>{{ $borrows->firstItem() + $index }}</td>
                        <td>
                            {{-- 🛠️ កែសម្រួល៖ ប្រើ $borrow->student_id ឬការពារដោយទិន្នន័យចម្បង --}}
                            @if($borrow->student)
                                <a href="{{ route('students.show', $borrow->student->id) }}" class="text-primary font-semibold text-decoration-none">
                                    {{ $borrow->student->name }}
                                </a>
                                <br><small class="text-muted">{{ $borrow->student->student_id }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        
                        <td>
                            @if($borrow->book)
                                <a href="{{ route('books.show', $borrow->book->id) }}" class="text-primary font-semibold text-decoration-none">
                                    {{ Str::limit($borrow->book->title, 25) }}
                                </a>
                            @else
                                <span class="text-danger small fw-bold">
                                    <i class="fas fa-exclamation-triangle"></i> {{ __('menu.book_deleted') }}
                                </span>
                            @endif
                        </td>

                        <td>{{ $borrow->borrow_date ? \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') : '—' }}</td>
                        <td>
                            @php
                                $isOverdue = $borrow->status === 'borrowed' && \Carbon\Carbon::parse($borrow->due_date)->isPast();
                            @endphp
                            <span class="{{ $isOverdue || $borrow->status === 'overdue' ? 'text-danger fw-bold' : '' }}">
                                {{ $borrow->due_date ? \Carbon\Carbon::parse($borrow->due_date)->format('d/m/Y') : '—' }}
                            </span>
                            @if($isOverdue || $borrow->status === 'overdue')
                                <br><small class="text-danger"><i class="fas fa-clock"></i> {{ __('menu.overdue') }}</small>
                                <br><small class="text-danger"><i class="fas fa-dollar-sign"></i> ${{ number_format($borrow->calculateFine(), 2) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($borrow->return_date)
                                <span class="text-success">{{ \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') }}</span>
                            @else
                                <span class="text-muted small">{{ __('menu.not_returned_yet') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($borrow->status === 'returned')
                                <span class="badge badge-success">{{ __('menu.status_returned') }}</span>
                            @elseif($borrow->status === 'overdue' || $isOverdue)
                                <span class="badge badge-danger">{{ __('menu.status_overdue') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('menu.status_borrowed') }}</span>
                            @endif
                        </td>
                        <td class="text-end" style="padding-right: 20px;">
                            <div class="action-buttons d-flex gap-1 justify-content-end">
                                @can('admin')
                                @if(in_array($borrow->status, ['borrowed', 'overdue']) || $isOverdue)
                                    <a href="{{ route('borrows.return.form', $borrow->id) }}" class="btn btn-sm btn-success" title="{{ __('menu.return') }}">
                                        <i class="fas fa-check-circle"></i> {{ __('menu.return') }}
                                    </a>
                                @endif
                                @endcan

                                <a href="{{ route('borrows.print', $borrow->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('menu.print_receipt') }}">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route('borrows.show', $borrow->id) }}" class="btn btn-sm btn-info" title="{{ __('menu.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @can('admin')
                                <form action="{{ route('borrows.destroy', $borrow->id) }}" method="POST" class="d-inline" data-confirm="{{ json_encode(['message' => __('menu.delete_record_confirm')]) }}">
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
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-history fa-3x mb-3 text-secondary"></i><br>
                            <h5>{{ __('menu.no_borrows_found') }}</h5>
                            <p class="text-sm">{{ __('menu.filter') }} @can('admin')<a href="{{ route('borrows.create') }}" class="text-primary">{{ __('menu.issue_new_book') }}</a>@endcan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="pagination-info text-muted small">
            {{ __('menu.showing_results', ['from' => $borrows->firstItem() ?? 0, 'to' => $borrows->lastItem() ?? 0, 'total' => $borrows->total()]) }}
        </div>
        <div>
            {{ $borrows->links() }}
        </div>
    </div>
</div>
@endsection