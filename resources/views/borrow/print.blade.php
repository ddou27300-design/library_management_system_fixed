<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('menu.borrow_receipt') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { margin: 0.3in; size: A4 portrait; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

        body {
            font-family: 'DM Sans', 'Segoe UI', Arial, sans-serif;
            color: #1e293b; background: #fff;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; padding: 20px;
        }
        body.lang-kh {
            font-family: 'Hanuman', 'DM Sans', 'Segoe UI', Arial, sans-serif;
        }

        .receipt {
            width: 380px; max-width: 100%;
            background: #fff; border: 2px dashed #cbd5e1;
            border-radius: 12px; padding: 24px 20px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
        }

        .receipt-header {
            text-align: center; padding-bottom: 14px;
            border-bottom: 2px double #1e293b; margin-bottom: 16px;
        }
        .receipt-header .lib-name {
            font-size: 14px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.5px; color: #0f172a;
        }
        .receipt-header .receipt-title {
            font-size: 11px; color: #64748b; text-transform: uppercase;
            letter-spacing: 2px; margin-top: 2px; font-weight: 600;
        }
        .receipt-header .divider-dots {
            margin: 6px auto 0; width: 60px;
            border-top: 2px dotted #94a3b8;
        }

        .receipt-body { font-size: 12px; line-height: 1.6; }

        .info-row {
            display: flex; justify-content: space-between; align-items: baseline;
            padding: 4px 0; border-bottom: 1px dotted #e2e8f0;
        }
        .info-row:last-child { border-bottom: none; }

        .info-label {
            font-weight: 600; color: #64748b; font-size: 10px;
            text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0;
        }
        .info-value {
            text-align: right; color: #1e293b; font-weight: 500;
            max-width: 60%;
        }
        .info-value.code {
            font-family: 'Courier New', monospace;
            font-weight: 700; font-size: 13px; letter-spacing: 0.5px;
            color: #0f172a;
        }

        .section-title {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #94a3b8; margin: 12px 0 6px;
        }

        .receipt-divider {
            border: none; border-top: 1px dashed #cbd5e1;
            margin: 12px 0;
        }

        .receipt-footer {
            text-align: center; padding-top: 12px;
            border-top: 1px dashed #cbd5e1; margin-top: 12px;
        }
        .receipt-footer .stamp-row {
            display: flex; justify-content: space-between; margin-top: 20px;
            padding: 0 4px;
        }
        .receipt-footer .stamp-item {
            text-align: center; flex: 1;
        }
        .receipt-footer .stamp-label {
            font-size: 8px; color: #94a3b8; text-transform: uppercase;
            letter-spacing: 1px; margin-bottom: 28px;
        }
        .receipt-footer .stamp-line {
            border-top: 1.5px solid #1e293b; padding-top: 3px;
            font-size: 10px; font-weight: 600; color: #1e293b;
        }
        .receipt-footer .thanks {
            font-size: 11px; font-weight: 600; color: #0f172a;
            margin-top: 14px; letter-spacing: 0.5px;
        }
        .receipt-footer .timestamp {
            font-size: 8px; color: #94a3b8; margin-top: 8px;
        }
        .receipt-footer .powered {
            font-size: 7px; color: #cbd5e1; margin-top: 2px;
        }

        .receipt-note {
            background: #f8fafc; border-radius: 6px;
                padding: 8px 10px; margin-top: 8px; font-size: 11px;
            color: #475569; border-left: 3px solid #3b82f6;
            line-height: 1.5;
        }

        .print-hint {
            text-align: center; margin-top: 16px; font-size: 11px;
            color: #64748b; background: #f1f5f9; border-radius: 8px;
            padding: 10px 14px; border: 1px solid #e2e8f0;
        }
        .print-hint button {
            background: #0f172a; color: #fff; border: none;
            padding: 8px 20px; border-radius: 6px; font-size: 12px;
            font-weight: 600; cursor: pointer; margin: 0 4px;
        }
        .print-hint button:hover { background: #1e293b; }
        .print-hint .skip-link {
            color: #94a3b8; font-size: 11px; text-decoration: underline;
            cursor: pointer; margin-left: 8px;
        }
        .print-hint .skip-link:hover { color: #64748b; }

        @media print {
            .print-hint { display: none !important; }
        }
    </style>
</head>
<body class="{{ app()->getLocale() === 'kh' ? 'lang-kh' : '' }}">
    <div class="receipt">
        <div class="receipt-header">
            <img src="{{ asset('images/logo.png') }}" alt="Library" style="width:56px;height:56px;border-radius:10px;object-fit:cover;margin-bottom:4px;">
            <div class="lib-name">{{ __('menu.library_ms') }}</div>
            <div class="receipt-title">{{ __('menu.borrow_receipt') }}</div>
            <div class="divider-dots"></div>
        </div>

        <div class="receipt-body">
            <div class="section-title">{{ __('menu.borrow_code') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.code') }}</span>
                <span class="info-value code">{{ $borrow->borrow_code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.status') }}</span>
                <span class="info-value">{{ __('menu.status_' . $borrow->status) }}</span>
            </div>

            <hr class="receipt-divider">

            <div class="section-title">{{ __('menu.student_info') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.name') }}</span>
                <span class="info-value">{{ $borrow->student->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.student_id') }}</span>
                <span class="info-value code">{{ $borrow->student->student_id }}</span>
            </div>
            @if($borrow->student->email)
            <div class="info-row">
                <span class="info-label">{{ __('menu.email') }}</span>
                <span class="info-value">{{ $borrow->student->email }}</span>
            </div>
            @endif
            @if($borrow->student->phone)
            <div class="info-row">
                <span class="info-label">{{ __('menu.phone') }}</span>
                <span class="info-value">{{ $borrow->student->phone }}</span>
            </div>
            @endif

            <hr class="receipt-divider">

            <div class="section-title">{{ __('menu.book_info_borrow') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.book_title') }}</span>
                <span class="info-value">{{ $borrow->book->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.book_author') }}</span>
                <span class="info-value">{{ $borrow->book->author }}</span>
            </div>
            @if($borrow->book->isbn)
            <div class="info-row">
                <span class="info-label">{{ __('menu.book_isbn') }}</span>
                <span class="info-value code">{{ $borrow->book->isbn }}</span>
            </div>
            @endif

            <hr class="receipt-divider">

            <div class="section-title">{{ __('menu.borrow_information') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.borrow_date') }}</span>
                <span class="info-value">{{ $borrow->borrow_date->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('menu.due_date') }}</span>
                <span class="info-value" style="{{ $borrow->isOverdue() ? 'color:#dc2626;font-weight:700;' : '' }}">
                    {{ $borrow->due_date->format('d M Y') }}
                </span>
            </div>
            @if($borrow->return_date)
            <div class="info-row">
                <span class="info-label">{{ __('menu.return_date') }}</span>
                <span class="info-value">{{ $borrow->return_date->format('d M Y') }}</span>
            </div>
            @endif
            @if($borrow->notes)
            <div class="receipt-note">{{ $borrow->notes }}</div>
            @endif
        </div>

        <div class="receipt-footer">
            <div class="stamp-row">
                <div class="stamp-item">
                    <div class="stamp-label">{{ __('menu.issued_by') }}</div>
                    <div class="stamp-line">{{ $borrow->issuedBy->name ?? '____________________' }}</div>
                </div>
                <div class="stamp-item">
                    <div class="stamp-label">{{ __('menu.student') }}</div>
                    <div class="stamp-line">____________________</div>
                </div>
                <div class="stamp-item">
                    <div class="stamp-label">{{ __('menu.date') }}</div>
                    <div class="stamp-line">{{ now()->format('d M Y') }}</div>
                </div>
            </div>

            <div class="thanks">{{ __('menu.thank_you_borrow') }}</div>
            <div class="timestamp">{{ __('menu.printed_on') }} {{ now()->format('d M Y, h:i A') }}</div>
            <div class="powered">{{ __('menu.footer_copyright', ['year' => date('Y')]) }}</div>
        </div>
    </div>

    <div class="print-hint" id="printHint">
        <strong style="color:#0f172a;">&#x1F5A8; {{ __('menu.printer_ready') }}</strong>
        <p style="margin:6px 0 10px;">{{ __('menu.print_instruction') }}</p>
        <button onclick="window.print()"><i class="fas fa-print"></i> {{ __('menu.print_now') }}</button>
        <span class="skip-link" onclick="window.close()">{{ __('menu.cancel') }}</span>
    </div>

    <script>
        setTimeout(function() { window.print(); }, 500);
        window.onafterprint = function() {
            document.getElementById('printHint').style.display = 'none';
        };
    </script>
</body>
</html>