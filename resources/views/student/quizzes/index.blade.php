@extends('layouts.app')

@section('title', 'Available Quizzes')

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
    <a href="{{ route('student.quizzes') }}" class="nav-link active">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('student.quizzes.results') }}" class="nav-link">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>
@endsection

@section('content')
<h2 class="mb-4">Available Quizzes</h2>

<div class="row g-4">
    @forelse($quizzes as $quiz)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">{{ $quiz->title }}</h5>
                    <p class="text-muted small mb-3">By {{ $quiz->user->name }}</p>
                    
                    @if($quiz->description)
                        <p class="card-text text-muted small mb-3">{{ Str::limit($quiz->description, 100) }}</p>
                    @endif
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-0">{{ $quiz->questions_count }}</h6>
                                <small class="text-muted">Questions</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-0">{{ $quiz->duration ?? 'Unlimited' }}</h6>
                                <small class="text-muted">{{ $quiz->duration ? 'Minutes' : 'Time' }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-0">{{ $quiz->passing_score }}%</h6>
                                <small class="text-muted">Pass</small>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('student.quizzes.take', $quiz) }}" class="btn btn-primary w-100">
                        <i class="fas fa-pen"></i> Take Quiz
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No quizzes available yet.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $quizzes->links() }}
</div>
@endsection