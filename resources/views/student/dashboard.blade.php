@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link active">
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
    <a href="{{ route('student.quizzes.results') }}" class="nav-link">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>
@endsection

@section('content')
<h2 class="mb-4">Welcome, {{ auth()->user()->name }}!</h2>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">Available Topics</h6>
                <h2 class="mb-0">{{ $stats['available_topics'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">Available Videos</h6>
                <h2 class="mb-0">{{ $stats['available_videos'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">Available Quizzes</h6>
                <h2 class="mb-0">{{ $stats['available_quizzes'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="mb-1">Completed Quizzes</h6>
                <h2 class="mb-0">{{ $stats['completed_quizzes'] }}</h2>
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
                        <a href="{{ route('student.topics.download', $topic) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-muted">No topics available yet.</p>
                @endforelse
                
                @if($recentTopics->count() > 0)
                    <a href="{{ route('student.topics') }}" class="btn btn-sm btn-outline-primary w-100">View All Topics</a>
                @endif
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
                        <a href="{{ route('student.videos.watch', $video) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-play"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-muted">No videos available yet.</p>
                @endforelse
                
                @if($recentVideos->count() > 0)
                    <a href="{{ route('student.videos') }}" class="btn btn-sm btn-outline-success w-100">View All Videos</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection