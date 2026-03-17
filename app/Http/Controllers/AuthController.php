<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AccountActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'nullable|in:admin,teacher,student',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // If role is provided, check if it matches
        if ($request->filled('role')) {
            if ($user->role !== $credentials['role']) {
                return back()->withErrors(['role' => 'Invalid role selected.'])->withInput();
            }
        }

        // If user is admin, allow login without role selection
        if ($user->role === 'admin') {
            Auth::login($user);
            $this->ensureWelcomeNotification($user);
            return redirect()->route('admin.dashboard')->with('login_success', true);
        }

        // For teacher and student, role must be selected
        if (!$request->filled('role')) {
            return back()->withErrors(['role' => 'Please select your role.'])->withInput();
        }

        if ($user->role === 'teacher' && !$user->is_approved) {
            return back()->withErrors(['email' => 'Your account is pending admin approval.'])->withInput();
        }

        Auth::login($user);
        $this->ensureWelcomeNotification($user);

        $redirects = [
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
        ];

        return redirect()->route($redirects[$user->role])->with('login_success', true);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'role' => 'required|in:teacher,student',
            'student_id' => 'nullable|string|max:255|unique:users,student_id',
            'course' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'email.unique' => 'This email is already registered. Please use a different email or login.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_approved' => $validated['role'] === 'student',
            'student_id' => $validated['role'] === 'student' ? $validated['student_id'] : null,
            'course' => $validated['role'] === 'student' ? $validated['course'] : null,
            'section' => $validated['role'] === 'student' ? $validated['section'] : null,
        ]);

        if (Schema::hasTable('notifications')) {
            $user->notify(new AccountActivityNotification(
                'Account Created',
                $validated['role'] === 'teacher'
                    ? 'Your teacher account has been created and is waiting for admin approval.'
                    : 'Your student account is ready. You can now log in and start learning.',
                route('login'),
                'Open Login'
            ));

            $adminLink = $validated['role'] === 'teacher'
                ? route('admin.teachers')
                : route('admin.students');

            User::where('role', 'admin')->get()->each(function (User $admin) use ($user, $validated, $adminLink) {
                $admin->notify(new AccountActivityNotification(
                    $validated['role'] === 'teacher' ? 'New Teacher Registration' : 'New Student Registration',
                    "{$user->name} created a {$validated['role']} account.",
                    $adminLink,
                    'Open'
                ));
            });
        }

        $message = $validated['role'] === 'teacher'
            ? 'Registration successful! Please wait for admin approval.'
            : 'Registration successful! Please login to continue.';

        if ($request->expectsJson() || $request->ajax()) {
            session()->flash('success', $message);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login')->with('success', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function ensureWelcomeNotification(User $user): void
    {
        if (!Schema::hasTable('notifications') || $user->notifications()->exists()) {
            return;
        }

        $routeName = match ($user->role) {
            'admin' => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            default => 'student.dashboard',
        };

        $user->notify(new AccountActivityNotification(
            'Welcome to LMS',
            'Your account is ready. Use the notification center to track updates.',
            route($routeName),
            'Open Dashboard'
        ));
    }
}
