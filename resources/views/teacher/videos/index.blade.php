@extends('layouts.app')

@section('title', 'My Videos')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('teacher.topics.index') }}" class="nav-link">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('teacher.videos.index') }}" class="nav-link active">
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
    <h2>My Videos</h2>
    <a href="{{ route('teacher.videos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Upload New Video
    </a>
</div>

<div class="row g-4">
    @forelse($videos as $video)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">{{ $video->title }}</h5>
                    <p class="text-muted small mb-3">{{ $video->created_at->format('M d, Y') }}</p>
                    
                    @if($video->description)
                        <p class="card-text text-muted small">{{ Str::limit($video->description, 100) }}</p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge bg-success">
                            <i class="fas fa-play"></i> Video
                        </span>
                        <form action="{{ route('teacher.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                <i class="fas fa-info-circle"></i> No videos uploaded yet. Click "Upload New Video" to add one.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $videos->links() }}
</div>
@endsection