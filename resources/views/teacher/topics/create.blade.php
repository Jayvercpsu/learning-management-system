@extends('layouts.app')

@section('title', 'Upload Topic')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('teacher.topics.index') }}" class="nav-link active">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('teacher.videos.index') }}" class="nav-link">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="{{ route('teacher.quizzes.index') }}" class="nav-link">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('teacher.students') }}" class="nav-link">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Upload New Topic</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.topics.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Topic Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.csv,.jpg,.jpeg,.png,.gif" required>
                        <small class="text-muted">Supported: PDF, Word, PowerPoint, Excel, CSV, Images (Max: 50MB)</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.topics.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Topic
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection