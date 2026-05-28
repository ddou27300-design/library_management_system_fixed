<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('avatar')) {
            $old = $user->avatar;
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');

            if ($old) {
                $history = $user->old_avatars ?? [];
                array_unshift($history, $old);
                $data['old_avatars'] = array_slice($history, 0, 10);
            }
        }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', __('menu.profile_updated'));
    }

    public function restoreAvatar($index)
    {
        $user = Auth::user();
        $history = $user->old_avatars ?? [];

        if (!isset($history[$index])) {
            return back()->with('error', 'Avatar not found.');
        }

        $restore = $history[$index];
        $current = $user->avatar;

        $newHistory = $history;
        unset($newHistory[$index]);
        $newHistory = array_values($newHistory);

        if ($current) {
            array_unshift($newHistory, $current);
        }

        $user->update([
            'avatar'      => $restore,
            'old_avatars' => array_slice($newHistory, 0, 10),
        ]);

        return redirect()->route('profile.show')
            ->with('success', __('menu.avatar_restored'));
    }

    public function deleteAvatar($index)
    {
        $user = Auth::user();
        $history = $user->old_avatars ?? [];

        if (!isset($history[$index])) {
            return back()->with('error', 'Avatar not found.');
        }

        Storage::disk('public')->delete($history[$index]);

        $newHistory = $history;
        unset($newHistory[$index]);
        $newHistory = array_values($newHistory);

        $user->update([
            'old_avatars' => $newHistory ?: null,
        ]);

        return redirect()->route('profile.show')
            ->with('success', __('menu.avatar_deleted'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => __('menu.old_password_incorrect'),
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', __('menu.password_changed'));
    }
}
