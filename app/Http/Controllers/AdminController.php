<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Quiz;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_teachers' => User::where('role', 'teacher')->count(),
            'pending_teachers' => User::where('role', 'teacher')->where('is_approved', false)->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_topics' => Topic::count(),
            'total_videos' => Video::count(),
            'total_quizzes' => Quiz::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->latest()->paginate(15);
        return view('admin.teachers', compact('teachers'));
    }

    public function approveTeacher(User $user)
    {
        if ($user->role !== 'teacher') {
            return back()->with('error', 'Invalid user role.');
        }

        $user->update(['is_approved' => true]);
        return back()->with('success', 'Teacher approved successfully.');
    }

    public function deleteTeacher(User $user)
    {
        if ($user->role !== 'teacher') {
            return back()->with('error', 'Invalid user role.');
        }

        $user->delete();
        return back()->with('success', 'Teacher deleted successfully.');
    }

    public function students()
    {
        $students = User::where('role', 'student')->latest()->paginate(15);
        return view('admin.students', compact('students'));
    }

    public function deleteStudent(User $user)
    {
        if ($user->role !== 'student') {
            return back()->with('error', 'Invalid user role.');
        }

        $user->delete();
        return back()->with('success', 'Student deleted successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);
        return redirect()->back()->with('success', 'User updated successfully.');
    }
}
