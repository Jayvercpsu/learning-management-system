<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TopicController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $topics = $user->topics()->latest()->paginate(12);

        return view('teacher.topics.index', compact('topics'));
    }

    public function create()
    {
        return view('teacher.topics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,jpg,jpeg,png,gif|max:51200',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('topics', $filename, 'public');

        Topic::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('teacher.topics.index')
            ->with('success', 'Topic uploaded successfully.');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($topic->file_path);
        $topic->delete();

        return back()->with('success', 'Topic deleted successfully.');
    }
}
