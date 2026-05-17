<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $year = now()->year;

        $monthlySummary = collect(range(1, 12))->map(function ($m) use ($year) {
            $date = Carbon::create($year, $m, 1);
            return [
                'month'    => $date->format('F'),
                'borrowed' => Borrow::whereYear('borrow_date', $year)->whereMonth('borrow_date', $m)->count(),
                'returned' => Borrow::where('status', 'returned')
                                    ->whereYear('return_date', $year)->whereMonth('return_date', $m)->count(),
                'fines'    => (float) Borrow::whereYear('return_date', $year)->whereMonth('return_date', $m)->sum('fine_amount'),
            ];
        });

        $categoryStats = Category::with(['books' => fn($q) => $q->withCount('borrows')])
            ->withCount('books')->get()->map(fn($c) => [
                'name'    => $c->name,
                'books'   => $c->books_count,
                'borrows' => $c->books->sum('borrows_count'),
            ]);

        $summary = [
            'total_borrows'   => Borrow::count(),
            'active_borrows'  => Borrow::whereIn('status', ['borrowed', 'overdue'])->count(),
            'overdue_count'   => Borrow::where('status', 'overdue')->count(),
            'total_fines'     => (float) Borrow::sum('fine_amount'),
            'fines_collected' => (float) Borrow::where('status', 'returned')->where('fine_amount', '>', 0)->sum('fine_amount'),
            'total_books'     => Book::count(),
            'total_students'  => Student::count(),
        ];

        return view('reports.index', compact('monthlySummary', 'categoryStats', 'summary'));
    }

    public function overdue()
    {
        Borrow::where('status', 'borrowed')
               ->where('due_date', '<', now()->toDateString())
               ->update(['status' => 'overdue']);

        $borrows = Borrow::where('status', 'overdue')
            ->with(['student', 'book'])
            ->orderBy('due_date')
            ->paginate(20);

        return view('reports.overdue', compact('borrows'));
    }

    public function fines(Request $request)
    {
        $query = Borrow::where('fine_amount', '>', 0)->with(['student', 'book']);

        if ($request->filled('from_date')) $query->whereDate('updated_at', '>=', $request->from_date);
        if ($request->filled('to_date'))   $query->whereDate('updated_at', '<=', $request->to_date);

        $borrows    = $query->latest()->paginate(15)->withQueryString();
        $totalFines = Borrow::where('fine_amount', '>', 0)->sum('fine_amount');

        return view('reports.fines', compact('borrows', 'totalFines'));
    }

    public function popular()
    {
        $books = Book::withCount('borrows')->orderByDesc('borrows_count')->paginate(20);
        return view('reports.popular', compact('books'));
    }
}