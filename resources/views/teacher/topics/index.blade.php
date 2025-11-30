@extends('layouts.app')

@section('title', 'My Topics')

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
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Topics</h2>
    <a href="{{ route('teacher.topics.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Upload New Topic
    </a>
</div>

<div class="row g-4">
    @forelse($topics as $topic)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{{ $topic->title }}</h5>
                            <small class="text-muted">{{ $topic->created_at->format('M d, Y') }}</small>
                        </div>
                        <span class="badge bg-primary">{{ strtoupper($topic->file_type) }}</span>
                    </div>
                    
                    @if($topic->description)
                        <p class="card-text text-muted small">{{ Str::limit($topic->description, 100) }}</p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-file"></i> {{ $topic->getFileSizeFormatted() }}
                        </small>
                        <form action="{{ route('teacher.topics.destroy', $topic) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                <i class="fas fa-info-circle"></i> No topics uploaded yet. Click "Upload New Topic" to add one.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>
@endsection
