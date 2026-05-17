<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// ✅ Only keep ONE Language Switch Route inside the 'web' group
Route::middleware(['web'])->group(function () {
    Route::get('lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'kh'])) {
            session()->put('locale', $locale);
            session()->save(); // Force save the session change immediately
        }
        return redirect()->back();
    })->name('lang.switch');
});

// Auth routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books - full CRUD
    Route::resource('books', BookController::class);

    // Categories - full CRUD
    Route::resource('categories', CategoryController::class);

    // Students - full CRUD
    Route::resource('students', StudentController::class);

    // Borrow & Return
    Route::prefix('borrows')->name('borrows.')->group(function () {
        Route::get('/',               [BorrowController::class, 'index'])->name('index');
        Route::get('/create',         [BorrowController::class, 'create'])->name('create');
        Route::post('/',              [BorrowController::class, 'store'])->name('store');
        Route::get('/{borrow}',       [BorrowController::class, 'show'])->name('show');
        Route::get('/{borrow}/return', [BorrowController::class, 'returnForm'])->name('return.form');
        Route::post('/{borrow}/return', [BorrowController::class, 'processReturn'])->name('return');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',        [ReportController::class, 'index'])->name('index');
        Route::get('/overdue', [ReportController::class, 'overdue'])->name('overdue');
        Route::get('/fines',   [ReportController::class, 'fines'])->name('fines');
        Route::get('/popular', [ReportController::class, 'popular'])->name('popular');
    });

    // Staff management (admin only)
    Route::middleware('can:admin')->group(function () {
        Route::resource('staff', StaffController::class)->except(['show']);
        // Keep legacy /register route working for backward compatibility
        Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });
});