@extends('layouts.app')

@section('title', 'Edit Quiz')

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
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Edit Quiz: {{ $quiz->title }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('teacher.quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quiz Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $quiz->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration (minutes, optional)</label>
                    <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $quiz->duration) }}" min="1">
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description (Optional)</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $quiz->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Passing Score (%)</label>
                <input type="number" name="passing_score" class="form-control @error('passing_score') is-invalid @enderror" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                @error('passing_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Note: To edit questions, please create a new quiz. Question editing is not available for quizzes with existing attempts.
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
