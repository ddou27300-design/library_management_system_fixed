<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::withCount(['borrows', 'activeBorrows']);

        if ($request->filled('search')) $query->search($request->search);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('major'))  $query->where('major', $request->major);

        $students = $query->latest()->paginate(15)->withQueryString();
        $majors   = Student::distinct()->pluck('major')->filter()->sort()->values();

        return view('students.index', compact('students', 'majors'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|string|max:20|unique:students,student_id',
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|unique:students,email',
            'phone'      => 'nullable|string|max:20',
            'class'      => 'nullable|string|max:50',
            'major'      => 'nullable|string|max:100',
            'address'    => 'nullable|string',
            'status'     => 'required|in:active,inactive,suspended',
        ]);

        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student "' . $data['name'] . '" registered successfully.');
    }

    public function show(Student $student)
    {
        $borrowHistory = $student->borrows()->with('book')->latest()->paginate(10);
        return view('students.show', compact('student', 'borrowHistory'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_id' => 'required|string|max:20|unique:students,student_id,' . $student->id,
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|unique:students,email,' . $student->id,
            'phone'      => 'nullable|string|max:20',
            'class'      => 'nullable|string|max:50',
            'major'      => 'nullable|string|max:100',
            'address'    => 'nullable|string',
            'status'     => 'required|in:active,inactive,suspended',
        ]);

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->activeBorrows()->exists()) {
            return back()->with('error', 'Cannot delete a student with active borrows.');
        }
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student removed.');
    }
}