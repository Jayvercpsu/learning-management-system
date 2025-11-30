<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Exceptions\PostTooLargeException;

class VideoController extends Controller
{
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
            $path = $file->storeAs('videos', $filename, 'public');

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

        Storage::disk('public')->delete($video->video_path);
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        $video->delete();

        return back()->with('success', 'Video deleted successfully.');
    }
}