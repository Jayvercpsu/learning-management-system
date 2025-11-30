<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Quiz;

class TeacherController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = auth()->user();

        $stats = [
            'total_topics'  => $user->topics()->count(),
            'total_videos'  => $user->videos()->count(),
            'total_quizzes' => $user->quizzes()->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];

        $recentTopics = $user->topics()->latest()->take(5)->get();
        $recentVideos = $user->videos()->latest()->take(5)->get();

        return view('teacher.dashboard', compact('stats', 'recentTopics', 'recentVideos'));
    }

    public function students()
    {
        $students = User::where('role', 'student')->latest()->paginate(15);
        return view('teacher.students', compact('students'));
    }
}
