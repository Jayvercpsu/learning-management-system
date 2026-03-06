<?php

namespace App\Http\Controllers;

use App\Services\MediaStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct(private MediaStorageService $mediaStorage)
    {
    }

    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048',
            'student_id' => 'nullable|string|max:255|unique:users,student_id,' . $user->id,
            'course' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                $this->mediaStorage->delete($user->profile_picture);
            }

            $validated['profile_picture'] =
                $this->mediaStorage->store($request->file('profile_picture'), 'profiles');
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
