@extends('layouts.app')
@section('title', __('menu.fines_title'))
@section('page-title', __('menu.fines_title'))

@push('styles')
<style>
.fine-total-banner {
    background: linear-gradient(135deg,#d97706,#f59e0b);
    color:#fff; border-radius:12px; padding:20px 24px;
    display:flex; align-items:center; gap:16px; margin-bottom:24px;
}
.fine-total-banner i { font-size:2rem; opacity:.8; }
.fine-total-banner .amt { font-size:2rem; font-weight:700; }
.fine-total-banner .lbl { font-size:.85rem; opacity:.85; }
.badge-paid   { background:#dcfce7;color:#16a34a; }
.badge-unpaid { background:#fef2f2;color:#dc2626; }
</style>
@endpush

@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> {{ __('menu.back_to_reports') }}
    </a>
</div>

{{-- Total Banner --}}
<div class="fine-total-banner">
    <i class="fas fa-dollar-sign"></i>
    <div>
        <div class="lbl">{{ __('menu.total_fines_all') }}</div>
        <div class="amt">${{ number_format($totalFines, 2) }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-dollar-sign" style="color:#d97706"></i> {{ __('menu.fine_records') }}</h3>
    </div>

    {{-- Date filter --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('reports.fines') }}" class="filter-form">
            <label style="font-size:.85rem;font-weight:600;color:var(--text-muted)">{{ __('menu.from') }}</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
            <label style="font-size:.85rem;font-weight:600;color:var(--text-muted)">{{ __('menu.to') }}</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> {{ __('menu.filter') }}</button>
            <a href="{{ route('reports.fines') }}" class="btn btn-outline-secondary">{{ __('menu.reset') }}</a>
        </form>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('menu.borrow_code') }}</th>
                    <th>{{ __('menu.student') }}</th>
                    <th>{{ __('menu.book_title') }}</th>
                    <th>{{ __('menu.due_date') }}</th>
                    <th>{{ __('menu.return_date') }}</th>
                    <th>{{ __('menu.fine') }}</th>
                    <th>{{ __('menu.status') }}</th>
                    <th>{{ __('menu.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                <tr>
                    <td class="text-mono text-sm">{{ $borrow->borrow_code }}</td>
                    <td>
                        <a href="{{ route('students.show', $borrow->student) }}" class="text-primary fw-600">
                            {{ $borrow->student->name ?? '—' }}
                        </a>
                    </td>
                    <td>{{ Str::limit($borrow->book->title ?? '—', 28) }}</td>
                    <td>{{ $borrow->due_date->format('d M Y') }}</td>
                    <td>{{ $borrow->return_date ? $borrow->return_date->format('d M Y') : '—' }}</td>
                    <td style="font-weight:700;color:#d97706">${{ number_format($borrow->fine_amount, 2) }}</td>
                    <td>
                        @if($borrow->status === 'returned')
                            <span class="badge badge-paid">{{ __('menu.paid_returned') }}</span>
                        @else
                            <span class="badge badge-unpaid">{{ __('menu.unpaid') }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('borrows.show', $borrow) }}" class="btn btn-sm btn-info" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(in_array($borrow->status, ['borrowed','overdue']))
                            <a href="{{ route('borrows.return.form', $borrow) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-undo"></i> {{ __('menu.return') }}
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted" style="padding:40px">
                        <i class="fas fa-dollar-sign" style="font-size:2rem;opacity:.2;display:block;margin-bottom:8px"></i>
                        {{ __('menu.no_fine_records') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="pagination-info">
            {{ __('menu.showing_results', ['from' => $borrows->firstItem() ?? 0, 'to' => $borrows->lastItem() ?? 0, 'total' => $borrows->total()]) }}
        </div>
        {{ $borrows->links() }}
    </div>
</div>
@endsection
