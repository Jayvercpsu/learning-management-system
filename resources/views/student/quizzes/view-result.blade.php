@extends('layouts.app')

@section('title', 'Quiz Result')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('student.topics') }}" class="nav-link">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('student.videos') }}" class="nav-link">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="{{ route('student.geogebra') }}" class="nav-link">
        <i class="fas fa-chart-line"></i> GeoGebra
    </a>
    <a href="{{ route('student.quizzes') }}" class="nav-link">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('student.quizzes.results') }}" class="nav-link active">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $attempt->quiz->title }}</h2>
        <p class="text-muted mb-0">Quiz Result</p>
    </div>
    <div class="d-flex gap-2">
        @if($attempt->is_checked && $attempt->score !== null)
            <a href="{{ route('student.quizzes.download', $attempt) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Download PDF
            </a>
        @endif
        <a href="{{ route('student.quizzes.results') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h6 class="mb-1">Score</h6>
                @if($attempt->is_checked && $attempt->score !== null)
                    <h2 class="mb-0">{{ number_format($attempt->score, 2) }}%</h2>
                @else
                    <h2 class="mb-0">Pending</h2>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h6 class="mb-1">Points Earned</h6>
                <h2 class="mb-0">{{ $attempt->earned_points }}/{{ $attempt->total_points }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h6 class="mb-1">Status</h6>
                @if($attempt->is_checked && $attempt->score !== null)
                    @if($attempt->score >= $attempt->quiz->passing_score)
                        <h2 class="mb-0"><i class="fas fa-check-circle text-success"></i></h2>
                    @else
                        <h2 class="mb-0"><i class="fas fa-times-circle text-danger"></i></h2>
                    @endif
                @else
                    <h2 class="mb-0"><i class="fas fa-clock text-warning"></i></h2>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h6 class="mb-1">Submitted</h6>
                <h6 class="mb-0">{{ $attempt->submitted_at->format('M d, Y') }}</h6>
                <small>{{ $attempt->submitted_at->format('H:i') }}</small>
            </div>
        </div>
    </div>
</div>

@if(!$attempt->is_checked)
    <div class="alert alert-warning mb-4">
        <i class="fas fa-hourglass-half"></i> Your quiz is being graded by the teacher. Please check back later for your results.
    </div>
@endif

@if($attempt->is_checked)
    @foreach($attempt->answers as $index => $answer)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                    <div>
                        @if($answer->is_correct)
                            <span class="badge bg-success">Correct</span>
                        @elseif($answer->is_correct === false)
                            <span class="badge bg-danger">Incorrect</span>
                        @else
                            <span class="badge bg-secondary">Graded</span>
                        @endif
                        <span class="badge bg-primary">{{ $answer->points_earned }}/{{ $answer->question->points }} points</span>
                    </div>
                </div>

                <p class="mb-3"><strong>{{ $answer->question->question }}</strong></p>

                @if($answer->question->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $answer->question->image) }}" alt="Question image" class="img-fluid" style="max-height: 300px;">
                    </div>
                @endif

                <div class="mb-2">
                    <strong>Your Answer:</strong>
                    <p class="mb-0">{{ $answer->answer }}</p>
                </div>

                @if($answer->question->type !== 'essay' && $answer->question->correct_answer)
                    <div class="alert alert-info mb-0">
                        <strong>Correct Answer:</strong> {{ $answer->question->correct_answer }}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif
@endsection

