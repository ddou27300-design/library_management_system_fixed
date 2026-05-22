@extends('layouts.app')

@section('title', 'Borrowing History')
@section('page-title', 'Borrowing Records')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history"></i> Borrowing History</h3>
        <a href="{{ route('borrows.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Issue New Book
        </a>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ route('borrows.index') }}" class="filter-form">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search student name, ID or book..." 
                class="form-control search-input"
            >
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Student Details</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th class="text-end" style="padding-right: 20px;">Actions</th>
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
                                    <i class="fas fa-exclamation-triangle"></i> Book Deleted
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
                                <br><small class="text-danger"><i class="fas fa-clock"></i> Overdue</small>
                            @endif
                        </td>
                        <td>
                            @if($borrow->return_date)
                                <span class="text-success">{{ \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') }}</span>
                            @else
                                <span class="text-muted small">Not Returned Yet</span>
                            @endif
                        </td>
                        <td>
                            @if($borrow->status === 'returned')
                                <span class="badge bg-success">Returned</span>
                            @elseif($borrow->status === 'overdue' || $isOverdue)
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-warning text-dark">Borrowed</span>
                            @endif
                        </td>
                        <td class="text-end" style="padding-right: 20px;">
                            <div class="action-buttons d-flex gap-1 justify-content-end">
                                {{-- 🛠️ កែសម្រួល៖ អនុញ្ញាតឱ្យចុច Return ទាំងស្ថានភាព borrowed និង overdue និងប្តូរទៅប្រើ $borrow->id --}}
                                @if(in_array($borrow->status, ['borrowed', 'overdue']) || $isOverdue)
                                    <a href="{{ route('borrows.return.form', $borrow->id) }}" class="btn btn-sm btn-success" title="Mark as Returned">
                                        <i class="fas fa-check-circle"></i> Return
                                    </a>
                                @endif

                                {{-- 🛠️ កែសម្រួល៖ ប្តូរទៅប្រើ $borrow->id ចំៗ ការពារការវង្វេង ID --}}
                                <a href="{{ route('borrows.show', $borrow->id) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- 🛠️ កែសម្រួល៖ ប្តូរទៅប្រើ $borrow->id ត្រង់ Form លុបទិន្នន័យ --}}
                                <form action="{{ route('borrows.destroy', $borrow->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
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
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-history fa-3x mb-3 text-secondary"></i><br>
                            <h5>No borrowing history found</h5>
                            <p class="text-sm">Try adjusting your filters or <a href="{{ route('borrows.create') }}" class="text-primary">issue a book to a student</a>.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="pagination-info text-muted small">
            Showing {{ $borrows->firstItem() ?? 0 }}–{{ $borrows->lastItem() ?? 0 }} of {{ $borrows->total() }} records
        </div>
        <div>
            {{ $borrows->links() }}
        </div>
    </div>
</div>
@endsection