@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@push('styles')
<style>
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: left 0.4s ease;
        z-index: 0;
    }

    .stat-card:hover::before {
        left: 0;
    }

    .stat-card .card-body {
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }

    .stat-card:hover .card-body h6,
    .stat-card:hover .card-body h2 {
        color: white;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .dashboard-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .topic-item,
    .video-item {
        transition: all 0.3s ease;
        padding: 12px;
        border-radius: 8px;
    }

    .topic-item:hover,
    .video-item:hover {
        background: linear-gradient(to right, rgba(102, 126, 234, 0.1), transparent);
        transform: translateX(5px);
    }
</style>
@endpush

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

<div class="row g-4">
    <div class="col-md-6">
        <div class="card dashboard-card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-book text-primary me-2"></i>Recent Topics</h5>
            </div>
            <div class="card-body">
                @forelse($recentTopics as $topic)
                    <div class="topic-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $topic->title }}</h6>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $topic->created_at->diffForHumans() }}</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No topics uploaded yet.</p>
                        <a href="{{ route('teacher.topics.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Topic
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card dashboard-card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-video text-primary me-2"></i>Recent Videos</h5>
            </div>
            <div class="card-body">
                @forelse($recentVideos as $video)
                    <div class="video-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $video->title }}</h6>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $video->created_at->diffForHumans() }}</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-video fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No videos uploaded yet.</p>
                        <a href="{{ route('teacher.videos.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Video
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection