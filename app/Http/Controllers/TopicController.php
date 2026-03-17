<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use App\Services\ActivityNotificationService;
use App\Services\MediaStorageService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    public function __construct(
        private MediaStorageService $mediaStorage,
        private ActivityNotificationService $activityNotifications
    )
    {
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $topics = $user->topics()->latest()->paginate(12);
        $this->attachOfficePreviewMetadata($topics);

        return view('teacher.topics.index', compact('topics'));
    }

    public function create()
    {
        $maxKb = (int) config('media.topic_upload.max_kb', 204800);
        $configuredMaxBytes = $maxKb * 1024;
        $phpUploadMaxBytes = $this->phpUploadLimitBytes();
        $effectiveMaxBytes = min($configuredMaxBytes, $phpUploadMaxBytes);

        return view('teacher.topics.create', [
            'topicConfiguredMaxMb' => (int) ceil($configuredMaxBytes / (1024 * 1024)),
            'topicEffectiveMaxMb' => (int) ceil($effectiveMaxBytes / (1024 * 1024)),
            'phpUploadMax' => (string) ini_get('upload_max_filesize'),
            'phpPostMax' => (string) ini_get('post_max_size'),
        ]);
    }

    public function store(Request $request)
    {
        $allowedExtensions = (array) config('media.topic_upload.extensions', [
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'jpg', 'jpeg', 'png', 'gif',
        ]);
        $allowedExtensions = array_values(array_unique(array_map(
            static fn (string $extension): string => strtolower(ltrim($extension, '.')),
            $allowedExtensions
        )));
        $maxKb = (int) config('media.topic_upload.max_kb', 204800);

        Log::info('Topic upload request received', [
            'user_id' => auth()->id(),
            'path' => $request->path(),
            'content_length' => $request->server('CONTENT_LENGTH'),
            'php_upload_max_filesize' => ini_get('upload_max_filesize'),
            'php_post_max_size' => ini_get('post_max_size'),
            'configured_max_kb' => $maxKb,
            'allowed_extensions' => $allowedExtensions,
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|extensions:' . implode(',', $allowedExtensions) . '|max:' . $maxKb,
        ], [
            'file.extensions' => 'Unsupported file type. Allowed: ' . strtoupper(implode(', ', $allowedExtensions)) . '.',
        ]);

        $file = $request->file('file');
        Log::info('Topic upload file validated', [
            'user_id' => auth()->id(),
            'original_name' => $file->getClientOriginalName(),
            'extension' => strtolower($file->getClientOriginalExtension()),
            'size_bytes' => $file->getSize(),
            'client_mime' => $file->getClientMimeType(),
            'detected_mime' => $file->getMimeType(),
        ]);

        $filename = time() . '_' . $file->getClientOriginalName();

        try {
            $path = $this->mediaStorage->store($file, 'topics', $filename);
        } catch (\Throwable $e) {
            Log::error('Topic upload failed during storage', [
                'user_id' => auth()->id(),
                'filename' => $filename,
                'extension' => strtolower($file->getClientOriginalExtension()),
                'size_bytes' => $file->getSize(),
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'Upload failed: ' . $e->getMessage())
                ->withInput();
        }

        $topic = Topic::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $path,
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'file_size' => $file->getSize(),
        ]);

        /** @var User $teacher */
        $teacher = auth()->user();
        $topicTitle = (string) ($topic->title ?: 'Untitled Topic');

        $this->activityNotifications->notifyUser(
            $teacher,
            'Topic Uploaded',
            'You uploaded a new topic: "' . $topicTitle . '".',
            route('teacher.topics.index'),
            'Open Topics'
        );

        $students = User::query()->where('role', 'student')->get();
        $this->activityNotifications->notifyUsers(
            $students,
            'New Topic Available',
            $teacher->name . ' uploaded "' . $topicTitle . '".',
            route('student.topics'),
            'View Topics'
        );

        return redirect()->route('teacher.topics.index')
            ->with('success', 'Topic uploaded successfully.');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->user_id !== auth()->id()) {
            abort(403);
        }

        $topicTitle = (string) ($topic->title ?: 'Untitled Topic');

        $this->mediaStorage->delete($topic->file_path);
        $topic->delete();

        /** @var User $teacher */
        $teacher = auth()->user();
        $this->activityNotifications->notifyUser(
            $teacher,
            'Topic Deleted',
            'You deleted the topic "' . $topicTitle . '".',
            route('teacher.topics.index'),
            'Open Topics'
        );

        return back()->with('success', 'Topic deleted successfully.');
    }

    private function phpUploadLimitBytes(): int
    {
        $uploadMax = $this->iniSizeToBytes((string) ini_get('upload_max_filesize'));
        $postMax = $this->iniSizeToBytes((string) ini_get('post_max_size'));

        if ($uploadMax === 0 && $postMax === 0) {
            return PHP_INT_MAX;
        }

        if ($uploadMax === 0) {
            return $postMax;
        }

        if ($postMax === 0) {
            return $uploadMax;
        }

        return min($uploadMax, $postMax);
    }

    private function iniSizeToBytes(string $size): int
    {
        $value = trim(strtolower($size));
        if ($value === '' || $value === '-1') {
            return 0;
        }

        $unit = substr($value, -1);
        $number = (float) $value;

        return match ($unit) {
            'g' => (int) round($number * 1024 * 1024 * 1024),
            'm' => (int) round($number * 1024 * 1024),
            'k' => (int) round($number * 1024),
            default => (int) round($number),
        };
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
}
