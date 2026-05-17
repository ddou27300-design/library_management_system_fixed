<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where('available_copies', '>', 0);
            } else {
                $query->where('available_copies', 0);
            }
        }

        $books      = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'isbn'           => 'nullable|string|max:20|unique:books,isbn',
            'category_id'    => 'required|exists:categories,id',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher'      => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'total_copies'   => 'required|integer|min:1',
            'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:available,unavailable',
        ]);

        $data['available_copies'] = $data['total_copies'];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Book "' . $data['title'] . '" added successfully.');
    }

    public function show(Book $book)
    {
        $book->load('category');
        $borrowHistory = $book->borrows()->with('student')->latest()->paginate(10);
        return view('books.show', compact('book', 'borrowHistory'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $borrowed = $book->total_copies - $book->available_copies;

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'isbn'           => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'category_id'    => 'required|exists:categories,id',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher'      => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'total_copies'   => 'required|integer|min:' . $borrowed,
            'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'         => 'required|in:available,unavailable',
        ]);

        $data['available_copies'] = $data['total_copies'] - $borrowed;

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        if ($book->borrows()->whereIn('status', ['borrowed', 'overdue'])->exists()) {
            return back()->with('error', 'Cannot delete a book that is currently borrowed.');
        }
        if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book "' . $book->title . '" deleted successfully.');
    }
}