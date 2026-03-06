<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Services\MediaStorageService;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(private MediaStorageService $mediaStorage)
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
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:512000',
            ]);

            $file = $request->file('video');
            
            if (!$file) {
                return back()->with('error', 'No video file was uploaded.')->withInput();
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $this->mediaStorage->store($file, 'videos', $filename);

            Video::create([
                'user_id' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'video_path' => $path,
            ]);

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

        $this->mediaStorage->delete($video->video_path);
        if ($video->thumbnail) {
            $this->mediaStorage->delete($video->thumbnail);
        }
        $video->delete();

        return back()->with('success', 'Video deleted successfully.');
    }
}
