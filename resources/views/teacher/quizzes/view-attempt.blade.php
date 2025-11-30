@extends('layouts.app')

@section('title', 'View Attempt')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('teacher.topics.index') }}" class="nav-link">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('teacher.videos.index') }}" class="nav-link">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="{{ route('teacher.quizzes.index') }}" class="nav-link active">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('teacher.students') }}" class="nav-link">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $quiz->title }}</h2>
        <p class="text-muted mb-0">Student: {{ $attempt->user->name }}</p>
    </div>
    <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Score</h6>
                @if($attempt->is_checked && $attempt->score !== null)
                    <h2 class="mb-0">{{ number_format($attempt->score, 2) }}%</h2>
                @else
                    <h2 class="mb-0">Not Graded</h2>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Points Earned</h6>
                <h2 class="mb-0">{{ $attempt->earned_points }}/{{ $attempt->total_points }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Submitted</h6>
                <h6 class="mb-0">{{ $attempt->submitted_at->format('M d, Y') }}</h6>
                <small>{{ $attempt->submitted_at->format('H:i') }}</small>
            </div>
        </div>
    </div>
</div>

@if(!$quiz->auto_check && !$attempt->is_checked)
    <form action="{{ route('teacher.quizzes.attempts.grade', $attempt) }}" method="POST" id="gradeForm">
        @csrf
@endif

        @foreach($attempt->answers as $index => $answer)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                        <div>
                            @if($attempt->is_checked)
                                @if($answer->is_correct)
                                    <span class="badge bg-success">Correct</span>
                                @elseif($answer->is_correct === false)
                                    <span class="badge bg-danger">Incorrect</span>
                                @endif
                            @endif
                            <span class="badge bg-primary">{{ $answer->question->points }} {{ Str::plural('point', $answer->question->points) }}</span>
                        </div>
                    </div>

                    <p class="mb-3"><strong>{{ $answer->question->question }}</strong></p>

                    @if($answer->question->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $answer->question->image) }}" alt="Question image" class="img-fluid" style="max-height: 300px;">
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Student's Answer:</strong>
                        <div class="alert alert-light">{{ $answer->answer }}</div>
                    </div>

                    @if($answer->question->type !== 'essay' && $answer->question->correct_answer)
                        <div class="alert alert-info">
                            <strong>Correct Answer:</strong> {{ $answer->question->correct_answer }}
                        </div>
                    @endif

                    @if(!$quiz->auto_check && !$attempt->is_checked)
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Points to Award (Max: {{ $answer->question->points }})</label>
                                <input type="number" name="grades[{{ $answer->id }}]" class="form-control" min="0" max="{{ $answer->question->points }}" value="{{ $answer->points_earned }}" required>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-{{ $answer->points_earned > 0 ? 'success' : 'danger' }}">
                            <strong>Points Awarded:</strong> {{ $answer->points_earned }}/{{ $answer->question->points }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

@if(!$quiz->auto_check && !$attempt->is_checked)
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-check"></i> Submit Grades
            </button>
        </div>
    </form>
@endif
@endsection
