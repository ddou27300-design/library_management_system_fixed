@extends('layouts.app')
@section('title', 'Overdue Books')
@section('page-title', 'Overdue Books')

@push('styles')
<style>
.overdue-badge { display:inline-flex; align-items:center; gap:5px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:6px; padding:3px 10px; font-size:.8rem; font-weight:600; }
.days-overdue { font-weight:700; color:#dc2626; }
.fine-est { color:#d97706; font-weight:600; }
</style>
@endpush

@section('content')

{{-- Back nav --}}
<div style="margin-bottom:16px">
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-exclamation-triangle" style="color:#dc2626"></i> Overdue Books</h3>
        <span class="overdue-badge"><i class="fas fa-clock"></i> {{ $borrows->total() }} overdue</span>
    </div>

    @if($borrows->total() === 0)
        <div class="card-body" style="text-align:center;padding:60px 20px">
            <i class="fas fa-check-circle" style="font-size:3rem;color:#16a34a;display:block;margin-bottom:12px"></i>
            <h3 style="color:#16a34a;margin:0 0 6px">All Good!</h3>
            <p class="text-muted">No overdue books at the moment.</p>
            <a href="{{ route('borrows.index') }}" class="btn btn-primary" style="margin-top:12px">View All Borrows</a>
        </div>
    @else
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Borrow Code</th>
                        <th>Student</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Days Overdue</th>
                        <th>Est. Fine</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                    @php
                        $daysOverdue = now()->diffInDays($borrow->due_date);
                        $estFine = $borrow->calculateFine();
                    @endphp
                    <tr style="background:#fff8f8">
                        <td class="text-mono text-sm">{{ $borrow->borrow_code }}</td>
                        <td>
                            <a href="{{ route('students.show', $borrow->student) }}" class="text-primary fw-600">
                                {{ $borrow->student->name ?? '—' }}
                            </a>
                            <br><small class="text-muted">{{ $borrow->student->student_id ?? '' }}</small>
                        </td>
                        <td>
                            <a href="{{ route('books.show', $borrow->book) }}" class="text-primary">
                                {{ Str::limit($borrow->book->title ?? '—', 30) }}
                            </a>
                            <br><small class="text-muted">{{ $borrow->book->author ?? '' }}</small>
                        </td>
                        <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                        <td class="text-danger fw-600">{{ $borrow->due_date->format('d M Y') }}</td>
                        <td><span class="days-overdue">{{ $daysOverdue }} day(s)</span></td>
                        <td><span class="fine-est">${{ number_format($estFine, 2) }}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('borrows.return.form', $borrow) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-undo"></i> Return
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <div class="pagination-info">
                Showing {{ $borrows->firstItem() }}–{{ $borrows->lastItem() }} of {{ $borrows->total() }} overdue records
            </div>
            {{ $borrows->links() }}
        </div>
    @endif
</div>
@endsection
