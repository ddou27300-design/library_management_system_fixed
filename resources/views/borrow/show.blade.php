@extends('layouts.app')
@section('title', __('menu.borrow_detail'))
@section('page-title', __('menu.borrow_detail'))

@push('styles')
<style>
@media print {
    @page { margin: 0.4in; size: A4 portrait; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    body * { visibility: hidden; }
    #print-area, #print-area * { visibility: visible; }
    #print-area {
        position: absolute; left: 0; top: 0; width: 100%;
        font-family: 'Hanuman', 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
    }
    .sidebar, .topbar, .page-footer, .sidebar-toggle,
    .topbar-right, .btn, .no-print, .flash-container { display: none !important; }

    .print-doc {
        max-width: 740px; margin: 0 auto;
        background: #fff; border-radius: 0;
    }

    .print-header {
        text-align: center; padding-bottom: 20px;
        border-bottom: 3px double #1e293b; margin-bottom: 28px;
    }
    .print-header .lib-name {
        font-size: 22px; font-weight: 700; letter-spacing: 1.5px;
        text-transform: uppercase; color: #0f172a;
    }
    .print-header .lib-sub {
        font-size: 11px; color: #64748b; margin-top: 2px; letter-spacing: 0.5px;
    }
    .print-header .doc-title {
        font-size: 16px; font-weight: 600; margin-top: 14px;
        color: #1e293b; text-transform: uppercase; letter-spacing: 2px;
    }
    .print-header .doc-divider {
        width: 80px; height: 3px; background: #1e293b;
        margin: 8px auto 0; border-radius: 2px;
    }

    .print-badge {
        display: inline-block; padding: 4px 14px; border-radius: 20px;
        font-size: 11px; font-weight: 600; letter-spacing: 0.5px;
    }
    .print-badge-returned { background: #dcfce7; color: #166534; }
    .print-badge-borrowed { background: #fef9c3; color: #854d0e; }
    .print-badge-overdue  { background: #fee2e2; color: #991b1b; }
    .print-badge-lost     { background: #f3e8ff; color: #6b21a8; }

    .print-code {
        font-family: 'Courier New', monospace; font-size: 14px;
        font-weight: 700; color: #0f172a; letter-spacing: 1px;
    }

    .print-body { padding: 0 4px; }

    .print-row {
        display: flex; gap: 24px; margin-bottom: 24px;
    }
    .print-col { flex: 1; min-width: 0; }

    .print-section {
        border: 1.5px solid #e2e8f0; border-radius: 8px;
        padding: 16px 18px; margin-bottom: 18px;
        page-break-inside: avoid;
    }
    .print-section-header {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; color: #64748b; margin-bottom: 12px;
        padding-bottom: 6px; border-bottom: 1px solid #e2e8f0;
    }
    .print-section-header span { color: #0f172a; }

    .print-table { width: 100%; border-collapse: collapse; }
    .print-table th, .print-table td {
        padding: 5px 4px; font-size: 12px; text-align: left;
        border-bottom: 1px dotted #e2e8f0; vertical-align: top;
    }
    .print-table th {
        font-weight: 600; color: #64748b; width: 120px;
        padding-right: 12px;
    }
    .print-table td { color: #1e293b; }
    .print-table tr:last-child th,
    .print-table tr:last-child td { border-bottom: none; }

    .print-status-row td { padding-top: 8px; border-top: 1px solid #e2e8f0; }

    .print-amount { font-weight: 700; font-size: 13px; }
    .print-amount-danger { color: #dc2626; }

    .print-footer {
        text-align: center; padding-top: 20px; margin-top: 8px;
        border-top: 2px solid #e2e8f0;
    }
    .print-footer .stamp-line {
        display: flex; justify-content: space-between; margin-top: 24px;
        padding: 0 20px;
    }
    .print-footer .stamp-box {
        text-align: center; min-width: 160px;
    }
    .print-footer .stamp-line-label {
        font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;
        margin-bottom: 36px;
    }
    .print-footer .stamp-line-under {
        border-top: 1px solid #1e293b; padding-top: 4px;
        font-size: 11px; font-weight: 600; color: #1e293b;
    }
    .print-footer .timestamp {
        font-size: 9px; color: #94a3b8; margin-top: 16px;
    }
    .print-footer .powered {
        font-size: 9px; color: #cbd5e1; margin-top: 2px;
    }
}

@media screen {
    .print-doc { max-width: 820px; margin: 0 auto; }
    .print-header, .print-body, .print-footer { display: none; }
    #print-area .card { margin-bottom: 0; }
    @media (max-width: 768px) {
        #print-area .detail-grid { grid-template-columns: 1fr; }
    }
}
</style>
@endpush

@section('content')
<div id="print-area">
    <div class="card form-card">
        <div class="card-header">
            <h3><i class="fas fa-file-alt"></i> {{ __('menu.borrow_record') }}</h3>
            <div class="no-print">
                @if(in_array($borrow->status, ['borrowed', 'overdue']))
                    <a href="{{ route('borrows.return.form', $borrow) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-undo"></i> {{ __('menu.process_return') }}
                    </a>
                @endif
                <a href="{{ route('borrows.print', $borrow) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> {{ __('menu.print_receipt') }}
                </a>
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
                            <td>
                                @php $displayFine = $borrow->status === 'returned' || $borrow->status === 'lost' ? $borrow->fine_amount : $borrow->calculateFine(); @endphp
                                <span class="{{ $displayFine > 0 ? 'text-danger font-bold' : '' }}">
                                    ${{ number_format($displayFine, 2) }}
                                </span>
                                @if($borrow->status === 'overdue' && $borrow->fine_amount == 0)
                                    <br><small class="text-danger">{{ __('menu.est_fine') }}</small>
                                @endif
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
                    <a href="{{ route('students.show', $borrow->student) }}" class="btn btn-outline-info btn-sm mt-2 no-print">
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
                    <a href="{{ route('books.show', $borrow->book) }}" class="btn btn-outline-info btn-sm mt-2 no-print">
                        {{ __('menu.view_book') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="print-doc">
        <div class="print-header">
            <div class="lib-name">{{ __('menu.library_ms') }}</div>
            <div class="lib-sub">Official Borrow Record &mdash; Library Transaction Document</div>
            <div class="doc-title">{{ __('menu.borrow_record') }}</div>
            <div class="doc-divider"></div>
        </div>

        <div class="print-body">
            <div class="print-row">
                <div class="print-col">
                    <div class="print-section">
                        <div class="print-section-header"><span>&#x1F4CB; {{ __('menu.borrow_information') }}</span></div>
                        <table class="print-table">
                            <tr>
                                <th>{{ __('menu.borrow_code') }}</th>
                                <td><span class="print-code">{{ $borrow->borrow_code }}</span></td>
                            </tr>
                            <tr>
                                <th>{{ __('menu.status') }}</th>
                                <td>
                                    <span class="print-badge print-badge-{{ $borrow->status }}">
                                        {{ __('menu.status_' . $borrow->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('menu.borrow_date') }}</th>
                                <td>{{ $borrow->borrow_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('menu.due_date') }}</th>
                                <td class="{{ $borrow->isOverdue() ? 'print-amount print-amount-danger' : '' }}">
                                    {{ $borrow->due_date->format('d M Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('menu.return_date') }}</th>
                                <td>{{ $borrow->return_date ? $borrow->return_date->format('d M Y') : '—' }}</td>
                            </tr>
                            <tr class="print-status-row">
                                <th>{{ __('menu.fine') }}</th>
                                <td>
                                    @php $displayFine = $borrow->status === 'returned' || $borrow->status === 'lost' ? $borrow->fine_amount : $borrow->calculateFine(); @endphp
                                    <span class="print-amount {{ $displayFine > 0 ? 'print-amount-danger' : '' }}">
                                        ${{ number_format($displayFine, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="print-row">
                <div class="print-col">
                    <div class="print-section">
                        <div class="print-section-header"><span>&#x1F9D1;&#x200D;&#x1F393; {{ __('menu.student_info') }}</span></div>
                        <table class="print-table">
                            <tr><th>{{ __('menu.name') }}</th><td>{{ $borrow->student->name }}</td></tr>
                            <tr><th>{{ __('menu.student_id') }}</th><td class="print-code">{{ $borrow->student->student_id }}</td></tr>
                            <tr><th>{{ __('menu.email') }}</th><td>{{ $borrow->student->email ?? '—' }}</td></tr>
                            <tr><th>{{ __('menu.phone') }}</th><td>{{ $borrow->student->phone ?? '—' }}</td></tr>
                            <tr><th>{{ __('menu.major') }}</th><td>{{ $borrow->student->major ?? '—' }}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="print-col">
                    <div class="print-section">
                        <div class="print-section-header"><span>&#x1F4DA; {{ __('menu.book_info_borrow') }}</span></div>
                        <table class="print-table">
                            <tr><th>{{ __('menu.book_title') }}</th><td>{{ $borrow->book->title }}</td></tr>
                            <tr><th>{{ __('menu.book_author') }}</th><td>{{ $borrow->book->author }}</td></tr>
                            <tr><th>{{ __('menu.book_isbn') }}</th><td class="print-code">{{ $borrow->book->isbn ?? '—' }}</td></tr>
                            <tr><th>{{ __('menu.book_category') }}</th><td>{{ $borrow->book->category->name }}</td></tr>
                            <tr><th>{{ __('menu.book_publisher') }}</th><td>{{ $borrow->book->publisher ?? '—' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            @if($borrow->notes)
            <div class="print-row">
                <div class="print-col">
                    <div class="print-section">
                        <div class="print-section-header"><span>&#x1F4DD; {{ __('menu.notes') }}</span></div>
                        <p style="margin:0;font-size:12px;color:#1e293b;line-height:1.6;">{{ $borrow->notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="print-row">
                <div class="print-col">
                    <div class="print-section" style="border-style:dashed;">
                        <div class="print-section-header"><span>&#x1F4C5; {{ __('menu.return_process') }}</span></div>
                        <table class="print-table">
                            <tr><th>{{ __('menu.issued_by') }}</th><td>{{ $borrow->issuedBy->name ?? '—' }}</td></tr>
                            @if($borrow->returnedTo)
                            <tr><th>{{ __('menu.returned_to') }}</th><td>{{ $borrow->returnedTo->name ?? '—' }}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="print-footer">
            <div class="stamp-line">
                <div class="stamp-box">
                    <div class="stamp-line-label">{{ __('menu.issued_by') }}</div>
                    <div class="stamp-line-under">{{ $borrow->issuedBy->name ?? '____________________' }}</div>
                </div>
                <div class="stamp-box">
                    <div class="stamp-line-label">{{ __('menu.return_process') }}</div>
                    <div class="stamp-line-under">{{ $borrow->returnedTo->name ?? '____________________' }}</div>
                </div>
                <div class="stamp-box">
                    <div class="stamp-line-label">{{ __('menu.date') }}</div>
                    <div class="stamp-line-under">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
            <div class="timestamp">{{ __('menu.printed_on') }} {{ now()->format('l, d F Y \\a\\t h:i A') }}</div>
            <div class="powered">{{ __('menu.footer_copyright', ['year' => date('Y')]) }}</div>
        </div>
    </div>
</div>
@endsection