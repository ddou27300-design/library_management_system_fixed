<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-update overdue status
        Borrow::where('status', 'borrowed')
               ->where('due_date', '<', now()->toDateString())
               ->update(['status' => 'overdue']);

        $stats = [
            'total_books'      => Book::count(),
            'available_books'  => Book::where('available_copies', '>', 0)->count(),
            'total_students'   => Student::where('status', 'active')->count(),
            'total_borrowed'   => Borrow::whereIn('status', ['borrowed', 'overdue'])->count(),
            'overdue_count'    => Borrow::where('status', 'overdue')->count(),
            'returned_today'   => Borrow::where('status', 'returned')
                                        ->whereDate('return_date', today())->count(),
            'borrowed_today'   => Borrow::whereDate('borrow_date', today())->count(),
            'total_categories' => Category::count(),
            'total_fines'      => Borrow::where('fine_amount', '>', 0)->sum('fine_amount'),
        ];

        $recentBorrows = Borrow::with(['student', 'book'])
            ->latest()->take(8)->get();

        $overdueBorrows = Borrow::where('status', 'overdue')
            ->with(['student', 'book'])
            ->orderBy('due_date')->take(6)->get();

        // Last 6 months chart data
        $chartData = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = Carbon::now()->subMonths($monthsAgo);
            return [
                'month'    => $date->format('M'),
                'borrowed' => Borrow::whereYear('borrow_date', $date->year)
                                    ->whereMonth('borrow_date', $date->month)->count(),
                'returned' => Borrow::where('status', 'returned')
                                    ->whereYear('return_date', $date->year)
                                    ->whereMonth('return_date', $date->month)->count(),
            ];
        });

        $topBooks = Book::withCount('borrows')
            ->orderByDesc('borrows_count')->take(5)->get();

        $categoryStats = Category::withCount('books')
            ->orderByDesc('books_count')->get();

        return view('dashboard.index', compact(
            'stats', 'recentBorrows', 'overdueBorrows',
            'chartData', 'topBooks', 'categoryStats'
        ));
    }
}