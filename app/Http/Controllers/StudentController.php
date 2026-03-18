<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Services\ActivityNotificationService;
use App\Services\MediaStorageService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function __construct(
        private MediaStorageService $mediaStorage,
        private ActivityNotificationService $activityNotifications
    )
    {
    }

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
        $this->attachOfficePreviewMetadata($topics);
        return view('student.topics', compact('topics'));
    }

    public function markTopicViewed(Topic $topic)
    {
        $this->notifyTopicInteraction($topic, 'viewed');

        return response()->json([
            'success' => true,
        ]);
    }

    public function openTopic(Topic $topic)
    {
        if ($this->mediaStorage->isRemotePath($topic->file_path)) {
            $remoteUrl = $topic->file_url;
            if (! $remoteUrl) {
                abort(404);
            }

            return redirect()->away($remoteUrl);
        }

        $path = $this->mediaStorage->localAbsolutePath($topic->file_path);
        if (! $path || ! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function downloadTopic(Topic $topic)
    {
        if ($this->mediaStorage->isRemotePath($topic->file_path)) {
            $remoteUrl = $topic->file_url;
            if (! $remoteUrl) {
                abort(404);
            }

            $this->notifyTopicInteraction($topic, 'downloaded');

            return redirect()->away($this->cloudinaryAttachmentUrl($remoteUrl));
        }

        $path = $this->mediaStorage->localAbsolutePath($topic->file_path);
        if (!$path || !file_exists($path)) {
            abort(404);
        }

        $this->notifyTopicInteraction($topic, 'downloaded');

        return response()->download($path, $this->topicDownloadFilename($topic));
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
            ->get();

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

    private function topicDownloadFilename(Topic $topic): string
    {
        $base = Str::slug($topic->title ?: ('topic-' . $topic->id));
        if ($base === '') {
            $base = 'topic-' . $topic->id;
        }

        $extension = strtolower((string) $topic->file_type);
        if ($extension !== '') {
            return $base . '.' . $extension;
        }

        return $base;
    }

    private function cloudinaryAttachmentUrl(string $url): string
    {
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        if ($host === '') {
            return $url;
        }

        $configuredCname = strtolower((string) config('media.cloudinary.cname', ''));
        $isCloudinaryHost = str_contains($host, 'res.cloudinary.com')
            || ($configuredCname !== '' && $host === $configuredCname);

        if (! $isCloudinaryHost) {
            return $url;
        }

        $path = trim((string) ($parts['path'] ?? ''), '/');
        if ($path === '') {
            return $url;
        }

        $segments = array_values(array_filter(explode('/', $path)));
        $uploadIndex = array_search('upload', $segments, true);
        if ($uploadIndex === false) {
            return $url;
        }

        $nextSegment = (string) ($segments[$uploadIndex + 1] ?? '');
        if (! str_contains($nextSegment, 'fl_attachment')) {
            array_splice($segments, $uploadIndex + 1, 0, ['fl_attachment']);
        }

        $scheme = (string) ($parts['scheme'] ?? 'https');
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        return $scheme . '://' . $host . $port . '/' . implode('/', $segments) . $query;
    }

    private function attachOfficePreviewMetadata(LengthAwarePaginator $topics): void
    {
        $topics->setCollection(
            $topics->getCollection()->map(function (Topic $topic): Topic {
                $isOfficeFile = $this->isOfficeExtension((string) $topic->file_type);

                $topic->setAttribute('is_office_file', $isOfficeFile);
                $topic->setAttribute('office_preview_url', $isOfficeFile ? $topic->office_preview_url : null);
                $topic->setAttribute('office_preview_fallback_url', $isOfficeFile ? $topic->office_preview_fallback_url : null);

                return $topic;
            })
        );
    }

    private function isOfficeExtension(string $extension): bool
    {
        return in_array(strtolower($extension), Topic::officeExtensions(), true);
    }

    private function notifyTopicInteraction(Topic $topic, string $action): void
    {
        /** @var User $student */
        $student = auth()->user();
        $topicTitle = (string) ($topic->title ?: 'Untitled Topic');
        $actionPast = strtolower(trim($action));

        $this->activityNotifications->notifyUser(
            $student,
            'Topic ' . ucfirst($actionPast),
            'You ' . $actionPast . ' "' . $topicTitle . '".',
            route('student.topics'),
            'Open Topics'
        );

        $teacher = $topic->user()->first();
        if (! $teacher instanceof User || $teacher->id === $student->id) {
            return;
        }

        $this->activityNotifications->notifyUser(
            $teacher,
            'Topic ' . ucfirst($actionPast) . ' by Student',
            $student->name . ' ' . $actionPast . ' your topic "' . $topicTitle . '".',
            route('teacher.topics.index'),
            'Open Topics'
        );
    }
}
