@extends('layouts.app')
@section('title', __('menu.return_book_title'))
@section('page-title', __('menu.process_return'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-undo"></i> {{ __('menu.return_book') }}</h3>
        <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
        </a>
    </div>
    <div class="card-body">
        @php
            $isOverdueRecord = $borrow->status === 'overdue' || ($borrow->due_date && \Carbon\Carbon::parse($borrow->due_date)->isPast());
        @endphp
        
        {{-- 🎨 កែសម្រួល៖ រក្សាទុកលក្ខខណ្ឌ Class ដើមរបស់លោកអ្នក --}}
        <div class="borrow-summary {{ $isOverdueRecord ? 'borrow-summary-danger' : 'borrow-summary-info' }}">
            <div class="summary-row">
                <span class="summary-label">{{ __('menu.borrow_code') }}</span>
                <span class="text-mono">{{ $borrow->borrow_code }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">{{ __('menu.student') }}</span>
                <span>
                    @if($borrow->student)
                        <strong>{{ $borrow->student->name }}</strong> ({{ $borrow->student->student_id }})
                    @else
                        <span class="text-danger font-bold">
                            <i class="fas fa-exclamation-triangle"></i> {{ __('menu.student_deleted') }}
                        </span>
                    @endif
                </span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">{{ __('menu.book_info_borrow') }}</span>
                <span>
                    @if($borrow->book)
                        <strong>{{ $borrow->book->title }}</strong> {{ __('menu.by_author') }} {{ $borrow->book->author }}
                    @else
                        <span class="text-danger font-bold">
                            <i class="fas fa-exclamation-triangle"></i> {{ __('menu.book_deleted') }}
                        </span>
                    @endif
                </span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">{{ __('menu.borrow_date') }}</span>
                <span>{{ $borrow->borrow_date ? \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') : '—' }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">{{ __('menu.due_date') }}</span>
                <span class="{{ $isOverdueRecord ? 'text-danger font-bold' : '' }}">
                    {{ $borrow->due_date ? \Carbon\Carbon::parse($borrow->due_date)->format('d M Y') : '—' }}
                    @if($isOverdueRecord)
                        {{-- 🎨 កែសម្រួល៖ ប្រើ class badge-danger (លំនាំដើមរបស់អ្នក) ការពារការបាត់ Style --}}
                        <span class="badge badge-danger">{{ now()->diffInDays($borrow->due_date) }} {{ __('menu.overdue_day_s') }}</span>
                    @endif
                </span>
            </div>
        </div>

        <form action="{{ route('borrows.return', $borrow->id) }}" method="POST" id="returnForm">
            @csrf

            {{-- 🎨 កែសម្រួល៖ ប្រើប្រាស់តែ <div class="form-row"> សុទ្ធ ដោយមិនបាច់ថែម row ឬ mb-3 នាំឱ្យជល់ CSS ដើម --}}
            <div class="form-row">
                <div class="form-group col-4">
                    <label for="return_date">{{ __('menu.return_date') }} <span class="required">*</span></label>
                    <input type="date" id="return_date" name="return_date"
                           class="form-control"
                           value="{{ old('return_date', today()->toDateString()) }}"
                           min="{{ $borrow->borrow_date ? \Carbon\Carbon::parse($borrow->borrow_date)->toDateString() : today()->toDateString() }}"
                           max="{{ today()->toDateString() }}"
                           onchange="calculateFine()" required>
                </div>
                <div class="form-group col-4">
                    <label for="condition">{{ __('menu.book_condition') }} <span class="required">*</span></label>
                    <select id="condition" name="condition" class="form-control" onchange="calculateFine()">
                        <option value="good">{{ __('menu.condition_good') }}</option>
                        <option value="damaged">{{ __('menu.condition_damaged') }}</option>
                        <option value="lost">{{ __('menu.condition_lost') }}</option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label>{{ __('menu.estimated_fine') }}</label>
                    {{-- 🎨 កែសម្រួល៖ បង្វិលមកប្រើ Class fine-display ដើមរបស់អ្នកវិញ --}}
                    <div class="fine-display" id="fineDisplay">$0.00</div>
                    <small class="text-muted">{{ __('menu.fine_rate', ['rate' => number_format(\App\Models\Borrow::FINE_PER_DAY, 2)]) }}</small>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">{{ __('menu.notes') }}</label>
                <textarea id="notes" name="notes" class="form-control" rows="2"
                          placeholder="{{ __('menu.notes_placeholder') }}">{{ old('notes') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> {{ __('menu.confirm_return') }}
                </button>
                <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dueDateString = '{{ $borrow->due_date ? \Carbon\Carbon::parse($borrow->due_date)->toDateString() : today()->toDateString() }}';
const dueDate       = new Date(dueDateString);
const finePerDay    = {{ \App\Models\Borrow::FINE_PER_DAY }};

function calculateFine() {
    const returnDateInput = document.getElementById('return_date').value;
    if (!returnDateInput) return;

    const returnDate  = new Date(returnDateInput);
    const condition   = document.getElementById('condition').value;
    let fine = 0;

    if (condition === 'lost') {
        fine = 10.00;
    } else if (returnDate > dueDate) {
        const days = Math.floor((returnDate - dueDate) / (1000 * 60 * 60 * 24));
        fine = days * finePerDay;
    }

    const display = document.getElementById('fineDisplay');
    display.textContent = '$' + fine.toFixed(2);
    
    // 🎨 កែសម្រួល៖ ប្រើប្រាស់ឈ្មោះ Class dynamic ដូចកូដចាស់របស់អ្នក (fine-amount-red និង fine-amount-zero)
    display.className = 'fine-display ' + (fine > 0 ? 'fine-amount-red' : 'fine-amount-zero');
}

calculateFine();
</script>
@endpush