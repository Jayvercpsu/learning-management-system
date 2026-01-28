@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('sidebar')
@include ('student.sidebar')
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

    .action-btn {
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }
</style>
@endpush

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
                            <a href="{{ route('student.topics.download', $topic) }}" class="btn btn-sm btn-primary action-btn">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No topics available yet.</p>
                    </div>
                @endforelse
                
                @if($recentTopics->count() > 0)
                    <a href="{{ route('student.topics') }}" class="btn btn-outline-primary w-100 mt-2">
                        View All Topics
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card dashboard-card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-video text-success me-2"></i>Recent Videos</h5>
            </div>
            <div class="card-body">
                @forelse($recentVideos as $video)
                    <div class="video-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $video->title }}</h6>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $video->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('student.videos.watch', $video) }}" class="btn btn-sm btn-success action-btn">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-video fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No videos available yet.</p>
                    </div>
                @endforelse
                
                @if($recentVideos->count() > 0)
                    <a href="{{ route('student.videos') }}" class="btn btn-outline-success w-100 mt-2">
                        View All Videos
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection