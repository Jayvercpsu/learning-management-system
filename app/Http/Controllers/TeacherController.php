<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\User;

class TeacherController extends Controller
{
    public function dashboard()
    {
        /** @var User $user */
        $user = auth()->user();

        $stats = [
            'total_topics' => $user->topics()->count(),
            'total_videos' => $user->videos()->count(),
            'total_quizzes' => $user->quizzes()->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];

        $recentTopics = $user->topics()->latest()->take(5)->get();
        $recentVideos = $user->videos()->latest()->take(5)->get();

        return view('teacher.dashboard', compact('stats', 'recentTopics', 'recentVideos'));
    }

    public function students()
    {
        $students = User::where('role', 'student')
            ->latest()
            ->get();

        return view('teacher.students', compact('students'));
    }

    public function studentProgress($studentId)
    {
        $student = User::where('role', 'student')->findOrFail($studentId);

        $allAttempts = QuizAttempt::with('quiz')
            ->where('user_id', $student->id)
            ->whereNotNull('submitted_at')
            ->latest('submitted_at')
            ->get();

        $quizResults = $allAttempts->map(function ($attempt) {
            $attempt->duration_display = $this->formatTotalTime($this->calculateDurationSeconds($attempt));
            return $attempt;
        });

        $scoredAttempts = $allAttempts->whereNotNull('score');
        $totalSeconds = $allAttempts->sum(function ($attempt) {
            return $this->calculateDurationSeconds($attempt);
        });

        $progress = [
            'quizzes_taken' => $allAttempts->count(),
            'average_score' => $scoredAttempts->avg('score') ?? 0,
            'highest_score' => $scoredAttempts->max('score') ?? 0,
            'lowest_score' => $scoredAttempts->min('score') ?? 0,
            'total_time' => $this->formatTotalTime($totalSeconds),
        ];

        $recentActivities = $this->getRecentActivities($allAttempts);
        $sectionMates = User::where('role', 'student')
            ->when($student->course, function ($query) use ($student) {
                $query->where('course', $student->course);
            })
            ->when(
                $student->section,
                fn ($query) => $query->where('section', $student->section),
                fn ($query) => $query->where(function ($innerQuery) {
                    $innerQuery->whereNull('section')->orWhere('section', '');
                })
            )
            ->orderBy('name')
            ->get();

        $sectionSummary = User::where('role', 'student')
            ->selectRaw("COALESCE(NULLIF(section, ''), 'Unassigned') as section_label, COUNT(*) as total")
            ->groupBy('section_label')
            ->orderBy('section_label')
            ->get();

        return view('teacher.progress', compact(
            'student',
            'quizResults',
            'progress',
            'recentActivities',
            'sectionMates',
            'sectionSummary'
        ));
    }

    /**
     * Format total time spent in a readable format.
     */
    private function formatTotalTime($seconds): string
    {
        $seconds = (int) $seconds;

        if ($seconds <= 0) {
            return '0m 0s';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        if ($minutes > 0) {
            return $minutes . 'm ' . $remainingSeconds . 's';
        }

        return $remainingSeconds . 's';
    }

    /**
     * Get recent student activities based on actual submitted attempts.
     */
    private function getRecentActivities($quizResults)
    {
        return $quizResults
            ->sortByDesc(function ($attempt) {
                return $attempt->submitted_at ?? $attempt->created_at;
            })
            ->take(10)
            ->map(function ($attempt) {
                $quizTitle = $attempt->quiz->title ?? 'Deleted quiz';
                $scoreText = $attempt->score !== null
                    ? number_format((float) $attempt->score, 1) . '%'
                    : 'Pending grade';

                return (object) [
                    'created_at' => $attempt->submitted_at ?? $attempt->created_at,
                    'description' => "Completed {$quizTitle} with {$scoreText}",
                ];
            });
    }

    /**
     * Calculate quiz attempt duration in seconds.
     */
    private function calculateDurationSeconds(QuizAttempt $attempt): int
    {
        if (!$attempt->started_at || !$attempt->submitted_at) {
            return 0;
        }

        return max(0, $attempt->started_at->diffInSeconds($attempt->submitted_at));
    }
}
