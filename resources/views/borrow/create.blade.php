@extends('layouts.app')
@section('title', 'Issue Book')
@section('page-title', 'Issue Book to Student')

@section('content')
<div class="card form-card">
    <div class="card-header">
        <h3><i class="fas fa-hand-holding-heart"></i> Issue Book</h3>
        <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('borrows.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group col-6">
                    <label for="student_id">Student <span class="required">*</span></label>
                    <select id="student_id" name="student_id"
                            class="form-control @error('student_id') is-invalid @enderror" required>
                        <option value="">— Select Student —</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                {{ (old('student_id', request('student_id')) == $student->id) ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->student_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-6">
                    <label for="book_id">Book <span class="required">*</span></label>
                    <select id="book_id" name="book_id"
                            class="form-control @error('book_id') is-invalid @enderror" required>
                        <option value="">— Select Book —</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}"
                                {{ (old('book_id', request('book_id')) == $book->id) ? 'selected' : '' }}>
                                {{ $book->title }} — {{ $book->author }} ({{ $book->available_copies }} left)
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-4">
                    <label for="borrow_date">Borrow Date <span class="required">*</span></label>
                    <input type="date" id="borrow_date" name="borrow_date"
                        class="form-control @error('borrow_date') is-invalid @enderror"
                        value="{{ old('borrow_date', today()->toDateString()) }}"
                        max="{{ today()->toDateString() }}" required>
                    @error('borrow_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-4">
                    <label for="due_date">Due Date <span class="required">*</span></label>
                    <input type="date" id="due_date" name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date', today()->addDays(14)->toDateString()) }}"
                        min="{{ today()->addDay()->toDateString() }}" required>
                    @error('due_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-4">
                    <label>Loan Period</label>
                    <div class="quick-days">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDays(7)">7 days</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setDays(14)">14 days</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setDays(30)">30 days</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="notes">Notes (optional)</label>
                <textarea id="notes" name="notes" class="form-control" rows="2"
                        placeholder="Any special notes about this borrow...">{{ old('notes') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Issue Book
                </button>
                <a href="{{ route('borrows.index') }}" class="btn btn-outline-secondary">Cancel</a>
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