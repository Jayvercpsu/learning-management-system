@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link active">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('teacher.topics.index') }}" class="nav-link">
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
<h2 class="mb-4">Teacher Dashboard</h2>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">My Topics</h6>
                <h2 class="mb-0">{{ $stats['total_topics'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">My Videos</h6>
                <h2 class="mb-0">{{ $stats['total_videos'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">My Quizzes</h6>
                <h2 class="mb-0">{{ $stats['total_quizzes'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">Total Students</h6>
                <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Topics</h5>
            </div>
            <div class="card-body">
                @forelse($recentTopics as $topic)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1">{{ $topic->title }}</h6>
                            <small class="text-muted">{{ $topic->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No topics uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Videos</h5>
            </div>
            <div class="card-body">
                @forelse($recentVideos as $video)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1">{{ $video->title }}</h6>
                            <small class="text-muted">{{ $video->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No videos uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
