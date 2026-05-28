<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', fn() => redirect()->route('login'));

// Language Switch
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'kh'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

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

    // Books
    Route::get('/books',                        [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create',                 [BookController::class, 'create'])->name('books.create')->middleware('role:admin');
    Route::post('/books',                       [BookController::class, 'store'])->name('books.store')->middleware('role:admin');
    Route::get('/books/{book}',                 [BookController::class, 'show'])->name('books.show');
    Route::get('/books/{book}/edit',            [BookController::class, 'edit'])->name('books.edit')->middleware('role:admin');
    Route::put('/books/{book}',                 [BookController::class, 'update'])->name('books.update')->middleware('role:admin');
    Route::delete('/books/{book}',              [BookController::class, 'destroy'])->name('books.destroy')->middleware('role:admin');

    // Categories
    Route::get('/categories',                        [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create',                 [CategoryController::class, 'create'])->name('categories.create')->middleware('role:admin');
    Route::post('/categories',                       [CategoryController::class, 'store'])->name('categories.store')->middleware('role:admin');
    Route::get('/categories/{category}',             [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit',        [CategoryController::class, 'edit'])->name('categories.edit')->middleware('role:admin');
    Route::put('/categories/{category}',             [CategoryController::class, 'update'])->name('categories.update')->middleware('role:admin');
    Route::delete('/categories/{category}',          [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('role:admin');

    // Students
    Route::get('/students',                          [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create',                   [StudentController::class, 'create'])->name('students.create')->middleware('role:admin');
    Route::post('/students',                         [StudentController::class, 'store'])->name('students.store')->middleware('role:admin');
    Route::get('/students/{student}',                [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit',           [StudentController::class, 'edit'])->name('students.edit')->middleware('role:admin');
    Route::put('/students/{student}',                [StudentController::class, 'update'])->name('students.update')->middleware('role:admin');
    Route::delete('/students/{student}',             [StudentController::class, 'destroy'])->name('students.destroy')->middleware('role:admin');

    // Borrow
    Route::get('/borrows',                           [BorrowController::class, 'index'])->name('borrows.index');
    Route::get('/borrows/create',                    [BorrowController::class, 'create'])->name('borrows.create')->middleware('role:admin');
    Route::post('/borrows',                          [BorrowController::class, 'store'])->name('borrows.store')->middleware('role:admin');
    Route::get('/borrows/{borrow}',                  [BorrowController::class, 'show'])->name('borrows.show');
    Route::get('/borrows/{borrow}/return',           [BorrowController::class, 'returnForm'])->name('borrows.return.form')->middleware('role:admin');
    Route::post('/borrows/{borrow}/return',          [BorrowController::class, 'processReturn'])->name('borrows.return')->middleware('role:admin');
    Route::get('/borrows/{borrow}/print',            [BorrowController::class, 'printReceipt'])->name('borrows.print');
    Route::delete('/borrows/{borrow}',               [BorrowController::class, 'destroy'])->name('borrows.destroy')->middleware('role:admin');

    // Profile — all authenticated users manage their own profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',                             [ProfileController::class, 'show'])->name('show');
        Route::put('/',                             [ProfileController::class, 'update'])->name('update');
        Route::put('/password',                     [ProfileController::class, 'changePassword'])->name('password');
        Route::post('/avatar/restore/{index}',      [ProfileController::class, 'restoreAvatar'])->name('avatar.restore');
        Route::delete('/avatar/delete/{index}',     [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    });

    // Reports — read only
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',        [ReportController::class, 'index'])->name('index');
        Route::get('/overdue', [ReportController::class, 'overdue'])->name('overdue');
        Route::get('/fines',   [ReportController::class, 'fines'])->name('fines');
        Route::get('/popular', [ReportController::class, 'popular'])->name('popular');
    });

    // Staff management — admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('staff', StaffController::class)->except(['show']);
        Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });
});