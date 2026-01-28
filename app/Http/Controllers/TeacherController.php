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

    public function studentProgress($studentId)
    {
        // Get the student with their related data
        $student = \App\Models\User::where('role', 'student')
            ->findOrFail($studentId);

        // Get all quiz attempts for this student
        $quizResults = \App\Models\QuizAttempt::with('quiz')
            ->where('user_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate progress statistics
        $progress = [
            'quizzes_taken' => $quizResults->count(),
            'average_score' => $quizResults->avg('score') ?? 0,
            'highest_score' => $quizResults->max('score') ?? 0,
            'lowest_score' => $quizResults->min('score') ?? 0,
            'total_time' => $this->formatTotalTime($quizResults->sum('time_spent')),
        ];

        // Get recent activities
        $recentActivities = $this->getRecentActivities($studentId, $quizResults);

        return view('teacher.progress', compact(
            'student',
            'quizResults',
            'progress',
            'recentActivities'
        ));
    }

    /**
     * Format total time spent in a readable format
     */
    private function formatTotalTime($seconds)
    {
        if (!$seconds) {
            return '0m';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . 'm';
    }

    /**
     * Get recent activities for the student
     */
    private function getRecentActivities($studentId, $quizResults)
    {
        $activities = collect();

        // Get recent quiz attempts (limit to 10)
        foreach ($quizResults->take(10) as $result) {
            // Skip if quiz was deleted
            if (!$result->quiz) {
                continue;
            }

            $scoreEmoji = $result->score >= 90 ? '🎉' : ($result->score >= 75 ? '👍' : '📝');
            $activities->push((object)[
                'created_at' => $result->created_at,
                'description' => "{$scoreEmoji} Completed quiz: {$result->quiz->title} - Score: " . number_format($result->score, 0) . "%"
            ]);
        }

        return $activities->sortByDesc('created_at')->take(10);
    }
}
