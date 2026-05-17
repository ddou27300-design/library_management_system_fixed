<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::orderBy('role')->orderBy('name')->paginate(20);
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,librarian'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff account for "' . $request->name . '" created successfully.');
    }

    public function edit(User $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $staff->id],
            'role'  => ['required', 'in:admin,librarian'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['min:8', 'confirmed'];
        }

        $request->validate($rules);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $staff->update($data);

        return redirect()->route('staff.index')
            ->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $staff)
    {
        // Prevent self-deletion
        if ($staff->id === Auth::id()) {
            return redirect()->route('staff.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $name = $staff->name;
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', '"' . $name . '" has been removed from staff.');
    }
}
