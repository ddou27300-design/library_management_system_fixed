@extends('layouts.app')
@section('title', __('menu.borrow_detail'))
@section('page-title', __('menu.borrow_detail'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-file-alt"></i> {{ __('menu.borrow_record') }}</h3>
        <div>
            @if(in_array($borrow->status, ['borrowed', 'overdue']))
                <a href="{{ route('borrows.return.form', $borrow) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-undo"></i> {{ __('menu.process_return') }}
                </a>
            @endif
            <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-section">
                <h4><i class="fas fa-barcode"></i> {{ __('menu.borrow_information') }}</h4>
                <table class="detail-table">
                    <tr><th>{{ __('menu.borrow_code') }}</th><td class="text-mono">{{ $borrow->borrow_code }}</td></tr>
                    <tr><th>{{ __('menu.status') }}</th><td><span class="badge badge-{{ $borrow->status }}">{{ __('menu.status_' . $borrow->status) }}</span></td></tr>
                    <tr><th>{{ __('menu.borrow_date') }}</th><td>{{ $borrow->borrow_date->format('d/m/Y') }}</td></tr>
                    <tr><th>{{ __('menu.due_date') }}</th>
                        <td class="{{ $borrow->isOverdue() ? 'text-danger font-bold' : '' }}">
                            {{ $borrow->due_date->format('d M Y') }}
                        </td>
                    </tr>
                    <tr><th>{{ __('menu.return_date') }}</th><td>{{ $borrow->return_date ? $borrow->return_date->format('d/m/Y') : '—' }}</td></tr>
                    <tr><th>{{ __('menu.fine') }}</th>
                        <td class="{{ $borrow->fine_amount > 0 ? 'text-danger font-bold' : '' }}">
                            ${{ number_format($borrow->fine_amount, 2) }}
                        </td>
                    </tr>
                    <tr><th>{{ __('menu.issued_by') }}</th><td>{{ $borrow->issuedBy->name ?? '—' }}</td></tr>
                    <tr><th>{{ __('menu.returned_to') }}</th><td>{{ $borrow->returnedTo->name ?? '—' }}</td></tr>
                    @if($borrow->notes)
                        <tr><th>{{ __('menu.notes') }}</th><td>{{ $borrow->notes }}</td></tr>
                    @endif
                </table>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-user-graduate"></i> {{ __('menu.student_info') }}</h4>
                <table class="detail-table">
                    <tr><th>{{ __('menu.name') }}</th><td>{{ $borrow->student->name }}</td></tr>
                    <tr><th>{{ __('menu.student_id') }}</th><td class="text-mono">{{ $borrow->student->student_id }}</td></tr>
                    <tr><th>{{ __('menu.email') }}</th><td>{{ $borrow->student->email ?? '—' }}</td></tr>
                    <tr><th>{{ __('menu.phone') }}</th><td>{{ $borrow->student->phone ?? '—' }}</td></tr>
                    <tr><th>{{ __('menu.major') }}</th><td>{{ $borrow->student->major ?? '—' }}</td></tr>
                </table>
                <a href="{{ route('students.show', $borrow->student) }}" class="btn btn-outline-info btn-sm mt-2">
                    {{ __('menu.view_profile') }}
                </a>
            </div>

            <div class="detail-section">
                <h4><i class="fas fa-book"></i> {{ __('menu.book_info_borrow') }}</h4>
                <table class="detail-table">
                    <tr><th>{{ __('menu.book_title') }}</th><td>{{ $borrow->book->title }}</td></tr>
                    <tr><th>{{ __('menu.book_author') }}</th><td>{{ $borrow->book->author }}</td></tr>
                    <tr><th>{{ __('menu.book_isbn') }}</th><td class="text-mono">{{ $borrow->book->isbn ?? '—' }}</td></tr>
                    <tr><th>{{ __('menu.book_category') }}</th><td>{{ $borrow->book->category->name }}</td></tr>
                    <tr><th>{{ __('menu.book_publisher') }}</th><td>{{ $borrow->book->publisher ?? '—' }}</td></tr>
                </table>
                <a href="{{ route('books.show', $borrow->book) }}" class="btn btn-outline-info btn-sm mt-2">
                    {{ __('menu.view_book') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection