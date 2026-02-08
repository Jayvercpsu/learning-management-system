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
    <div class="col-12">
        <div class="card dashboard-card border-0">
            <div class="card-header bg-gradient text-black" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2 text-black"></i>All About GeoGebra</h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="text-primary mb-3">What is GeoGebra?</h5>
                        <p class="mb-3">GeoGebra is a dynamic mathematics software that brings together geometry, algebra, statistics, and calculus in one easy-to-use package. It's designed to help students visualize and understand mathematical concepts through interactive explorations.</p>
                        
                        <h6 class="text-primary mb-2"><i class="fas fa-check-circle me-2"></i>Key Definitions</h6>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><strong>Postulate:</strong> A statement accepted as true without proof, serving as a starting point for mathematical reasoning.</li>
                            <li class="mb-2"><strong>Theorem:</strong> A mathematical statement that has been proven to be true based on previously established statements such as postulates and other theorems.</li>
                            <li class="mb-2"><strong>Construction:</strong> The process of drawing geometric figures using only a compass and straightedge, or digitally in GeoGebra.</li>
                        </ul>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Pro Tip:</strong> Watch the tutorial video to learn how to use GeoGebra effectively for your geometry lessons!
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="ratio ratio-16x9 mb-3">
                            <iframe src="https://www.youtube.com/embed/rEEAu5oAGUg" title="GeoGebra Tutorial - How to Use GeoGebra" allowfullscreen style="border: none;"></iframe>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="https://www.geogebra.org/" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>Visit GeoGebra Website
                            </a>
                            <a href="{{ route('student.topics') }}" class="btn btn-outline-primary">
                                <i class="fas fa-book me-2"></i>Explore Topics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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