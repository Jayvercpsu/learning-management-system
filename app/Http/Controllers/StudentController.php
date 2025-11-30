<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'available_topics' => Topic::count(),
            'available_videos' => Video::count(),
            'available_quizzes' => Quiz::count(),
            'completed_quizzes' => QuizAttempt::where('user_id', auth()->id())
                ->whereNotNull('submitted_at')
                ->count(),
        ];

        $recentTopics = Topic::latest()->take(5)->get();
        $recentVideos = Video::latest()->take(5)->get();

        return view('student.dashboard', compact('stats', 'recentTopics', 'recentVideos'));
    }

    public function topics()
    {
        $topics = Topic::with('user')->latest()->paginate(12);
        return view('student.topics', compact('topics'));
    }

    public function downloadTopic(Topic $topic)
    {
        $path = storage_path('app/public/' . $topic->file_path);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function videos()
    {
        $videos = Video::with('user')->latest()->paginate(12);
        return view('student.videos', compact('videos'));
    }

    public function watchVideo(Video $video)
    {
        return view('student.watch-video', compact('video'));
    }

    public function quizzes()
    {
        $quizzes = Quiz::with('user')
            ->withCount('questions')
            ->latest()
            ->paginate(12);
        
        return view('student.quizzes.index', compact('quizzes'));
    }

    public function quizResults()
    {
        $attempts = QuizAttempt::where('user_id', auth()->id())
            ->with('quiz')
            ->whereNotNull('submitted_at')
            ->latest()
            ->paginate(15);

        return view('student.quizzes.results', compact('attempts'));
    }

    public function viewResult(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $attempt->load(['quiz', 'answers.question']);
        return view('student.quizzes.view-result', compact('attempt'));
    }

    public function downloadResult(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $attempt->load(['quiz', 'answers.question', 'user']);

        $pdf = Pdf::loadView('student.quizzes.pdf-result', compact('attempt'));
        
        return $pdf->download('quiz-result-' . $attempt->id . '.pdf');
    }
}