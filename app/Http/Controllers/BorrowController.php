<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        // ធ្វើបច្ចុប្បន្នភាពស្ថានភាពហួសកាលកំណត់ (Sync overdue statuses)
        Borrow::where('status', 'borrowed')
               ->where('due_date', '<', now()->toDateString())
               ->update(['status' => 'overdue']);

        $query = Borrow::with(['student', 'book', 'issuedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('borrow_code', 'like', "%{$term}%")
                  ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$term}%")
                                                       ->orWhere('student_id', 'like', "%{$term}%"))
                  ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('borrow_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('borrow_date', '<=', $request->to_date);
        }

        $borrows = $query->latest()->paginate(15)->withQueryString();

        return view('borrow.index', compact('borrows'));
    }

    public function create(Request $request)
    {
        $students = Student::active()->orderBy('name')->get();
        $books    = Book::available()->with('category')->orderBy('title')->get();
        $selectedStudentId = $request->get('student_id');
        $selectedBookId    = $request->get('book_id');

        return view('borrow.create', compact('students', 'books', 'selectedStudentId', 'selectedBookId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'book_id'     => 'required|exists:books,id',
            'borrow_date' => 'required|date|before_or_equal:today',
            'due_date'    => 'required|date|after:borrow_date',
            'notes'       => 'nullable|string|max:500',
        ]);

        $book    = Book::findOrFail($data['book_id']);
        $student = Student::findOrFail($data['student_id']);

        if (!$book->isAvailable()) {
            return back()->withInput()->with('error', 'This book is not available for borrowing.');
        }
        if ($student->status !== 'active') {
            return back()->withInput()->with('error', 'This student account is not active.');
        }
        if ($student->borrows()->where('status', 'overdue')->exists()) {
            return back()->withInput()->with('error', 'Student has overdue books. Please resolve first.');
        }
        if ($student->borrows()->where('book_id', $book->id)->whereIn('status', ['borrowed','overdue'])->exists()) {
            return back()->withInput()->with('error', 'Student already has this book.');
        }

        DB::transaction(function () use ($data, $book) {
            Borrow::create(array_merge($data, [
                'status'    => 'borrowed',
                'issued_by' => Auth::id(),
            ]));
            $book->decrement('available_copies');
        });

        return redirect()->route('borrows.index')
            ->with('success', '"' . $book->title . '" issued successfully.');
    }

    // 🛠️ កែសម្រួល៖ ប្តូរពី (Borrow $borrow) ទៅទទួលយក $id ចំៗ ការពារការវង្វេង Key ជាមួយ Student
    public function show($id)
    {
        $borrow = Borrow::with(['student', 'book.category', 'issuedBy', 'returnedTo'])->findOrFail($id);
        return view('borrow.show', compact('borrow'));
    }

    // 🛠️ កែសម្រួល៖ ប្តូរទៅទទួលយក $id ចំៗ ការពារបញ្ហា Route គាំង
    public function returnForm($id)
    {
        $borrow = Borrow::findOrFail($id);
        
        if (in_array($borrow->status, ['returned', 'lost'])) {
            return redirect()->route('borrows.index')
                ->with('info', 'This record is already closed.');
        }
        $borrow->load(['student', 'book']);
        return view('borrow.return', compact('borrow'));
    }

    // 🛠️ ករណីដំណើរការត្រឡប់សៀវភៅ
    public function processReturn(Request $request, $id)
    {
        $borrow = Borrow::findOrFail($id);

        if (in_array($borrow->status, ['returned', 'lost'])) {
            return back()->with('error', 'This record is already closed.');
        }

        $data = $request->validate([
            'return_date' => 'required|date|after_or_equal:' . $borrow->borrow_date->toDateString(),
            'condition'   => 'required|in:good,damaged,lost',
            'notes'       => 'nullable|string|max:500',
        ]);

        $returnDate = Carbon::parse($data['return_date']);
        $fine       = 0;
        $newStatus  = 'returned';

        if ($data['condition'] === 'lost') {
            $newStatus = 'lost';
            $fine      = 10.00;
        } elseif ($returnDate->gt($borrow->due_date)) {
            $overdueDays = $borrow->due_date->diffInDays($returnDate);
            $fine = round($overdueDays * Borrow::FINE_PER_DAY, 2);
        }

        DB::transaction(function () use ($borrow, $returnDate, $newStatus, $fine, $data) {
            $borrow->update([
                'return_date' => $returnDate->toDateString(),
                'status'      => $newStatus,
                'fine_amount' => $fine,
                'notes'       => $data['notes'] ?? $borrow->notes,
                'returned_to' => Auth::id(),
            ]);

            if ($newStatus !== 'lost') {
                $borrow->book->increment('available_copies');
            }
        });

        $msg = 'Book returned successfully.';
        if ($fine > 0) $msg .= ' Fine: $' . number_format($fine, 2);

        return redirect()->route('borrows.index')->with('success', $msg);
    }

    public function printReceipt($id)
    {
        $borrow = Borrow::with(['student', 'book.category', 'issuedBy'])->findOrFail($id);
        return view('borrow.print', compact('borrow'));
    }

    /**
     * 🟢 បន្ថែមថ្មី៖ មុខងារលុបទិន្នន័យ (Destroy Method)
     * មុខងារនេះនឹងលុបកត់ត្រាខ្ចី ហើយបើវាជាសៀវភៅដែលមិនទាន់សង វានឹងបូកចំនួនសៀវភៅចូលក្នុងឃ្លាំងវិញ
     */
    public function destroy($id)
    {
        $borrow = Borrow::findOrFail($id);

        DB::transaction(function () use ($borrow) {
            // ប្រសិនបើលុបកត់ត្រាដែលកំពុងខ្ចី (មិនទាន់សង) ត្រូវឱ្យសៀវភៅនោះចូលឃ្លាំងវិញ
            if (in_array($borrow->status, ['borrowed', 'overdue'])) {
                if ($borrow->book) {
                    $borrow->book->increment('available_copies');
                }
            }
            
            // ធ្វើការលុបចេញពី Database
            $borrow->delete();
        });

        return redirect()->route('borrows.index')
            ->with('success', 'Borrowing record deleted successfully.');
    }
}