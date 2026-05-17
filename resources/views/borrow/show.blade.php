@extends('layouts.app')
@section('title', 'Borrow Detail')
@section('page-title', 'Borrow Detail')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-file-alt"></i> Borrow Record</h3>
        <div>
            @if(in_array($borrow->status, ['borrowed', 'overdue']))
                <a href="{{ route('borrows.return.form', $borrow) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-undo"></i> Process Return
                </a>
            @endif
            <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-section">
                <h4><i class="fas fa-barcode"></i> Borrow Information</h4>
                <table class="detail-table">
                    <tr><th>Borrow Code</th><td class="text-mono">{{ $borrow->borrow_code }}</td></tr>
                    <tr><th>Status</th><td><span class="badge badge-{{ $borrow->status }}">{{ ucfirst($borrow->status) }}</span></td></tr>
                    <tr><th>Borrow Date</th><td>{{ $borrow->borrow_date->format('d M Y') }}</td></tr>
                    <tr><th>Due Date</th>
                        <td class="{{ $borrow->isOverdue() ? 'text-danger font-bold' : '' }}">
                            {{ $borrow->due_date->format('d M Y') }}
                        </td>
                    </tr>
                    <tr><th>Return Date</th><td>{{ $borrow->return_date ? $borrow->return_date->format('d M Y') : '—' }}</td></tr>
                    <tr><th>Fine</th>
                        <td class="{{ $borrow->fine_amount > 0 ? 'text-danger font-bold' : '' }}">
                            ${{ number_format($borrow->fine_amount, 2) }}
                        </td>
                    </tr>
                    <tr><th>Issued By</th><td>{{ $borrow->issuedBy->name ?? '—' }}</td></tr>
                    <tr><th>Returned To</th><td>{{ $borrow->returnedTo->name ?? '—' }}</td></tr>
                    @if($borrow->notes)
                        <tr><th>Notes</th><td>{{ $borrow->notes }}</td></tr>
                    @endif
                </table>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-user-graduate"></i> Student</h4>
                <table class="detail-table">
                    <tr><th>Name</th><td>{{ $borrow->student->name }}</td></tr>
                    <tr><th>Student ID</th><td class="text-mono">{{ $borrow->student->student_id }}</td></tr>
                    <tr><th>Email</th><td>{{ $borrow->student->email ?? '—' }}</td></tr>
                    <tr><th>Phone</th><td>{{ $borrow->student->phone ?? '—' }}</td></tr>
                    <tr><th>Major</th><td>{{ $borrow->student->major ?? '—' }}</td></tr>
                </table>
                <a href="{{ route('students.show', $borrow->student) }}" class="btn btn-outline-info btn-sm mt-2">
                    View Profile
                </a>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-book"></i> Book</h4>
                <table class="detail-table">
                    <tr><th>Title</th><td>{{ $borrow->book->title }}</td></tr>
                    <tr><th>Author</th><td>{{ $borrow->book->author }}</td></tr>
                    <tr><th>ISBN</th><td class="text-mono">{{ $borrow->book->isbn ?? '—' }}</td></tr>
                    <tr><th>Category</th><td>{{ $borrow->book->category->name }}</td></tr>
                    <tr><th>Publisher</th><td>{{ $borrow->book->publisher ?? '—' }}</td></tr>
                </table>
                <a href="{{ route('books.show', $borrow->book) }}" class="btn btn-outline-info btn-sm mt-2">
                    View Book
                </a>
            </div>
        </div>
    </div>
</div>
@endsection