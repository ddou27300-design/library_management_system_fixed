@extends('layouts.app')
@section('title', $student->name)
@section('page-title', $student->name)

@push('styles')
<style>
@media (max-width: 480px) {
    .student-borrow-table th:nth-child(1), .student-borrow-table td:nth-child(1),
    .student-borrow-table th:nth-child(4), .student-borrow-table td:nth-child(4),
    .student-borrow-table th:nth-child(6), .student-borrow-table td:nth-child(6) { display: none; }
}
</style>
@endpush
@section('content')
<div class="row-2col">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-graduate"></i> {{ __('menu.student_info') }}</h3>
            <div>
                @can('admin')
                <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-edit"></i> {{ __('menu.edit') }}
                </a>
                @endcan
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="student-profile">
                <div class="avatar-lg">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                <h2 style="font-size:1.2rem;font-weight:700;">{{ $student->name }}</h2>
                <span class="badge badge-{{ $student->status === 'active' ? 'success' : ($student->status === 'suspended' ? 'danger' : 'secondary') }}">
                    {{ __('menu.status_' . $student->status) }}
                </span>
            </div>

            <table class="detail-table">
                <tr><th>{{ __('menu.student_id') }}</th><td class="text-mono">{{ $student->student_id }}</td></tr>
                <tr><th>{{ __('menu.email') }}</th><td>{{ $student->email ?? '—' }}</td></tr>
                <tr><th>{{ __('menu.phone') }}</th><td>{{ $student->phone ?? '—' }}</td></tr>
                <tr><th>{{ __('menu.class_major') }}</th><td>{{ $student->class ?? '—' }} @if($student->major)/ {{ $student->major }}@endif</td></tr>
                <tr><th>{{ __('menu.address') }}</th><td>{{ $student->address ?? '—' }}</td></tr>
                <tr><th>{{ __('menu.joined') }}</th><td>{{ $student->created_at->format('d M Y') }}</td></tr>
            </table>

            @php
                $totalFines = $student->borrows()->sum('fine_amount');
                $activeBorrows = $student->borrows()->whereIn('status', ['borrowed', 'overdue'])->count();
                $overdueCount = $student->borrows()->where('status', 'overdue')->count();
                $totalBorrows = $student->borrows()->count();
            @endphp
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <div style="text-align:center;">
                    <div style="font-size:1.3rem;font-weight:700;color:var(--primary);">{{ $totalBorrows }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">{{ __('menu.total_borrows') }}</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.3rem;font-weight:700;color:var(--warning);">{{ $activeBorrows }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">{{ __('menu.active_borrows') }}</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.3rem;font-weight:700;color:var(--danger);">{{ $overdueCount }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">{{ __('menu.overdue_lbl') }}</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.3rem;font-weight:700;color:var(--accent);">${{ number_format($totalFines, 2) }}</div>
                    <div style="font-size:.72rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">{{ __('menu.total_fines') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> {{ __('menu.borrow_history') }}</h3>
            @can('admin')
            <a href="{{ route('borrows.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> {{ __('menu.issue_book') }}
            </a>
            @endcan
        </div>
        <div class="card-body p-0">
            @if($borrowHistory->count() > 0)
            <table class="table student-borrow-table">
                <thead>
                    <tr>
                        <th>{{ __('menu.borrow_code') }}</th>
                        <th>{{ __('menu.book_title') }}</th>
                        <th>{{ __('menu.borrow_date') }}</th>
                        <th>{{ __('menu.due_date') }}</th>
                        <th>{{ __('menu.status') }}</th>
                        <th>{{ __('menu.fine') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowHistory as $borrow)
                    <tr>
                        <td class="text-mono">{{ $borrow->borrow_code }}</td>
                        <td>
                            @if($borrow->book)
                                <a href="{{ route('books.show', $borrow->book) }}">{{ Str::limit($borrow->book->title, 30) }}</a>
                            @else
                                <span class="text-danger">{{ __('menu.book_deleted') }}</span>
                            @endif
                        </td>
                        <td>{{ $borrow->borrow_date->format('d/m/Y') }}</td>
                        <td class="{{ $borrow->isOverdue() ? 'text-danger fw-bold' : '' }}">
                            {{ $borrow->due_date->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $borrow->status }}">
                                {{ __('menu.status_' . $borrow->status) }}
                            </span>
                        </td>
                        <td class="{{ $borrow->fine_amount > 0 ? 'text-danger fw-bold' : '' }}">
                            ${{ number_format($borrow->fine_amount > 0 ? $borrow->fine_amount : $borrow->calculateFine(), 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                <div class="pagination-info">
                    {{ __('menu.showing_results', ['from' => $borrowHistory->firstItem() ?? 0, 'to' => $borrowHistory->lastItem() ?? 0, 'total' => $borrowHistory->total()]) }}
                </div>
                {{ $borrowHistory->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <p>{{ __('menu.no_borrow_data') }}</p>
                @can('admin')
                <a href="{{ route('borrows.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm mt-3">
                    <i class="fas fa-plus"></i> {{ __('menu.issue_book') }}
                </a>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection