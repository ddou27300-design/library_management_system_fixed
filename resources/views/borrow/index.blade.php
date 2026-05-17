@extends('layouts.app')
@section('title', 'Borrow & Return')
@section('page-title', 'Borrow & Return Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-exchange-alt"></i> Borrow Records</h3>
        <a href="{{ route('borrows.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Issue Book
        </a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('borrows.index') }}" class="filter-form">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search student, book, borrow code..." class="form-control search-input">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
            </select>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" title="From Date">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" title="To Date">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Borrow Code</th>
                    <th>Student</th>
                    <th>Book</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Fine</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                    <tr class="{{ $borrow->isOverdue() ? 'row-danger' : '' }}">
                        <td class="text-mono text-sm">{{ $borrow->borrow_code }}</td>
                        <td>
                            <a href="{{ route('students.show', $borrow->student) }}" class="text-primary">
                                {{ $borrow->student->name ?? '—' }}
                            </a>
                            <br><small class="text-muted">{{ $borrow->student->student_id ?? '' }}</small>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $borrow->book) }}" class="text-primary">
                                {{ Str::limit($borrow->book->title ?? '—', 25) }}
                            </a>
                        </td>
                        <td>{{ $borrow->borrow_date->format('d/m/Y') }}</td>
                        <td class="{{ $borrow->isOverdue() ? 'text-danger font-bold' : '' }}">
                            {{ $borrow->due_date->format('d/m/Y') }}
                            @if($borrow->isOverdue())
                                <br><small class="text-danger">{{ now()->diffInDays($borrow->due_date) }}d overdue</small>
                            @endif
                        </td>
                        <td>{{ $borrow->return_date ? $borrow->return_date->format('d/m/Y') : '—' }}</td>
                        <td class="{{ $borrow->fine_amount > 0 ? 'text-danger font-bold' : '' }}">
                            {{ $borrow->fine_amount > 0 ? '$' . number_format($borrow->fine_amount, 2) : '—' }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $borrow->status }}">{{ ucfirst($borrow->status) }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array($borrow->status, ['borrowed', 'overdue']))
                                    <a href="{{ route('borrows.return.form', $borrow) }}" class="btn btn-sm btn-success" title="Return">
                                        <i class="fas fa-undo"></i> Return
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No borrow records found. <a href="{{ route('borrows.create') }}">Issue a book</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            Showing {{ $borrows->firstItem() }}–{{ $borrows->lastItem() }} of {{ $borrows->total() }} records
        </div>
        {{ $borrows->links() }}
    </div>
</div>
@endsection