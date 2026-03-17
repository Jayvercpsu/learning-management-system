<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Services\ActivityNotificationService;
use App\Services\MediaStorageService;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

class VideoController extends Controller
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
        
        $videos = $user->videos()->latest()->paginate(12);
        return view('teacher.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('teacher.videos.create');
    }

    public function store(Request $request)
    {
        try {
            $allowedExtensions = (array) config('media.video_upload.extensions', [
                'mp4', 'mov', 'avi', 'wmv',
            ]);
            $allowedExtensions = array_values(array_unique(array_map(
                static fn (string $extension): string => strtolower(ltrim($extension, '.')),
                $allowedExtensions
            )));
            $maxKb = (int) config('media.video_upload.max_kb', 512000);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'video' => 'required|file|extensions:' . implode(',', $allowedExtensions) . '|max:' . $maxKb,
            ], [
                'video.extensions' => 'Unsupported video type. Allowed: ' . strtoupper(implode(', ', $allowedExtensions)) . '.',
            ]);

            $file = $request->file('video');
            
            if (!$file) {
                return back()->with('error', 'No video file was uploaded.')->withInput();
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $this->mediaStorage->store($file, 'videos', $filename);

            $video = Video::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'video_path' => $path,
            ]);

            /** @var User $teacher */
            $teacher = auth()->user();
            $videoTitle = (string) ($video->title ?: 'Untitled Video');

            $this->activityNotifications->notifyUser(
                $teacher,
                'Video Uploaded',
                'You uploaded a new video: "' . $videoTitle . '".',
                route('teacher.videos.index'),
                'Open Videos'
            );

            $students = User::query()->where('role', 'student')->get();
            $this->activityNotifications->notifyUsers(
                $students,
                'New Video Available',
                $teacher->name . ' uploaded "' . $videoTitle . '".',
                route('student.videos'),
                'Watch Videos'
            );

            return redirect()->route('teacher.videos.index')
                ->with('success', 'Video uploaded successfully.');
                
        } catch (PostTooLargeException $e) {
            return back()->with('error', 'The video file is too large. Please upload a file smaller than 500MB.')
                ->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Video $video)
    {
        if ($video->user_id !== auth()->id()) {
            abort(403);
        }

        $videoTitle = (string) ($video->title ?: 'Untitled Video');
        $this->mediaStorage->delete($video->video_path);
        if ($video->thumbnail) {
            $this->mediaStorage->delete($video->thumbnail);
        }
        $video->delete();

        /** @var User $teacher */
        $teacher = auth()->user();
        $this->activityNotifications->notifyUser(
            $teacher,
            'Video Deleted',
            'You deleted the video "' . $videoTitle . '".',
            route('teacher.videos.index'),
            'Open Videos'
        );

        return back()->with('success', 'Video deleted successfully.');
    }
}
