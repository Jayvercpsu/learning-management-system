@extends('layouts.app')

@section('title', 'My Quizzes')

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
    <h2>My Quizzes</h2>
    <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create New Quiz
    </a>
</div>

<div class="row g-4">
    @forelse($quizzes as $quiz)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $quiz->title }}</h5>
                            <small class="text-muted">Created {{ $quiz->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($quiz->auto_check)
                            <span class="badge bg-success">Auto-check</span>
                        @else
                            <span class="badge bg-warning">Manual</span>
                        @endif
                    </div>
                    
                    @if($quiz->description)
                        <p class="card-text text-muted small">{{ Str::limit($quiz->description, 100) }}</p>
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
                                <h6 class="mb-0">{{ $quiz->attempts_count }}</h6>
                                <small class="text-muted">Attempts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="mb-0">{{ $quiz->passing_score }}%</h6>
                                <small class="text-muted">Pass</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-sm btn-info flex-grow-1">
                            <i class="fas fa-chart-bar"></i> Results
                        </a>
                        <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teacher.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No quizzes created yet. Click "Create New Quiz" to add one.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $quizzes->links() }}
</div>
@endsection

