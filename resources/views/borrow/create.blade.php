@extends('layouts.app')
@section('title', __('menu.issue_book_title'))
@section('page-title', __('menu.issue_book_title'))

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-hand-holding-heart"></i> {{ __('menu.issue_book') }}</h3>
        <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('menu.back') }}
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('borrows.store') }}" method="POST">
            @csrf

            <div class="form-row form-row-2">
                <div class="form-group">
                    <label for="student_id">{{ __('menu.student') }} <span class="required">*</span></label>
                    <select id="student_id" name="student_id"
                            class="form-control @error('student_id') is-invalid @enderror" required>
                        <option value="">{{ __('menu.select_student') }}</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ (old('student_id', request('student_id')) == $student->id) ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->student_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="book_id">{{ __('menu.book_info_borrow') }} <span class="required">*</span></label>
                    <select id="book_id" name="book_id"
                            class="form-control @error('book_id') is-invalid @enderror" required>
                        <option value="">{{ __('menu.select_book') }}</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}"
                                {{ (old('book_id', request('book_id')) == $book->id) ? 'selected' : '' }}>
                                {{ $book->title }} — {{ $book->author }} ({{ __('menu.copies_left', ['count' => $book->available_copies]) }})
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row form-row-3">
                <div class="form-group">
                    <label for="borrow_date">{{ __('menu.borrow_date') }} <span class="required">*</span></label>
                    <input type="date" id="borrow_date" name="borrow_date"
                        class="form-control @error('borrow_date') is-invalid @enderror"
                        value="{{ old('borrow_date', today()->toDateString()) }}"
                        max="{{ today()->toDateString() }}" required>
                    @error('borrow_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="due_date">{{ __('menu.due_date') }} <span class="required">*</span></label>
                    <input type="date" id="due_date" name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', today()->addDays(14)->toDateString()) }}"
                        min="{{ today()->addDay()->toDateString() }}" required>
                    @error('due_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>{{ __('menu.loan_period') }}</label>
                    <div class="quick-days">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDays(7)">7 days</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDays(14)">14 days</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDays(30)">30 days</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">{{ __('menu.notes_optional') }}</label>
                <textarea id="notes" name="notes" class="form-control" rows="2"
                        placeholder="{{ __('menu.notes_placeholder') }}">{{ old('notes') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> {{ __('menu.issue_book_btn') }}
                </button>
                <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">{{ __('menu.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function setDays(days) {
    const borrow = document.getElementById('borrow_date').value;
    if (!borrow) return;
    const due = new Date(borrow);
    due.setDate(due.getDate() + days);
    document.getElementById('due_date').value = due.toISOString().split('T')[0];
}
</script>
@endpush