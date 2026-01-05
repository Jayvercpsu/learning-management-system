<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class QuizController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        
        if ($user->isTeacher()) {
            $quizzes = $user->quizzes()->withCount('questions', 'attempts')->latest()->paginate(12);
            return view('teacher.quizzes.index', compact('quizzes'));
        }
    }

    public function create()
    {
        return view('teacher.quizzes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'auto_check' => 'required|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,essay',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice,true_false',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.image' => 'nullable|image|max:2048',
        ]);

        $quiz = Quiz::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'duration' => $validated['duration'],
            'passing_score' => $validated['passing_score'],
            'auto_check' => $validated['auto_check'],
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            $imagePath = null;
            if (isset($request->questions[$index]['image'])) {
                $image = $request->file("questions.$index.image");
                $imagePath = $image->store('quiz_attachments', 'public');
            }

            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $questionData['options'] ?? null,
                'correct_answer' => $questionData['correct_answer'] ?? null,
                'points' => $questionData['points'],
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz created successfully.');
    }

    public function edit(Quiz $quiz)
    {
        if ($quiz->user_id !== auth()->id()) {
            abort(403);
        }

        $quiz->load('questions');
        return view('teacher.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz->update($validated);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($quiz->questions as $question) {
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
        }

        $quiz->delete();
        return back()->with('success', 'Quiz deleted successfully.');
    }

    public function results(Quiz $quiz)
    {
        if ($quiz->user_id !== auth()->id()) {
            abort(403);
        }

        $attempts = $quiz->attempts()
            ->with('user')
            ->whereNotNull('submitted_at')
            ->latest()
            ->paginate(20);

        return view('teacher.quizzes.results', compact('quiz', 'attempts'));
    }

    public function viewAttempt(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($quiz->user_id !== auth()->id() || $attempt->quiz_id !== $quiz->id) {
            abort(403);
        }

        $attempt->load(['answers.question', 'user']);
        return view('teacher.quizzes.view-attempt', compact('quiz', 'attempt'));
    }

    public function gradeAttempt(Request $request, QuizAttempt $attempt)
    {
        $quiz = $attempt->quiz;
        
        if ($quiz->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'required|integer|min:0',
        ]);

        foreach ($validated['grades'] as $answerId => $points) {
            $answer = QuizAnswer::findOrFail($answerId);
            if ($answer->quiz_attempt_id !== $attempt->id) {
                abort(403);
            }

            $answer->update([
                'points_earned' => $points,
                'is_correct' => $points > 0,
            ]);
        }

        $attempt->is_checked = true;
        $attempt->save();
        $attempt->calculateScore();

        return back()->with('success', 'Quiz graded successfully.');
    }

    public function take(Quiz $quiz)
    {
        $quiz->load('questions');
        
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'started_at' => now(),
            'total_points' => $quiz->getTotalPoints(),
        ]);

        return view('student.quizzes.take', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->whereNull('submitted_at')
            ->latest()
            ->first();

        if (!$attempt) {
            return redirect()->route('student.quizzes')->with('error', 'Quiz attempt not found.');
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string',
        ]);

        foreach ($validated['answers'] as $questionId => $answer) {
            $question = QuizQuestion::findOrFail($questionId);
            
            $isCorrect = null;
            $pointsEarned = 0;

            if ($quiz->auto_check && $question->type !== 'essay') {
                if ($question->type === 'true_false' || $question->type === 'multiple_choice') {
                    $isCorrect = strtolower(trim($answer)) === strtolower(trim($question->correct_answer));
                    $pointsEarned = $isCorrect ? $question->points : 0;
                }
            }

            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $questionId,
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ]);
        }

        $attempt->submitted_at = now();
        
        if ($quiz->auto_check) {
            $attempt->is_checked = true;
        }
        
        $attempt->save();
        $attempt->calculateScore();

        return redirect()->route('student.quizzes.result', $attempt)->with('success', 'Quiz submitted successfully.');
    }
}