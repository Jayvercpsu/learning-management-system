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

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-7 p-4 text-white">
                        <h3 class="mb-3" style="font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                            <i class="fas fa-shapes me-2"></i>Teaching Geometry with GeoGebra
                        </h3>
                        <p class="mb-4" style="font-size: 1.05rem; line-height: 1.6; opacity: 0.95;">
                            Empower your students with interactive geometry lessons. Create dynamic demonstrations that bring abstract concepts to life and foster deeper understanding.
                        </p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-bookmark me-2"></i>Postulate</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">A statement accepted as true without proof, forming the foundation of geometric reasoning.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-certificate me-2"></i>Theorem</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">A proven mathematical statement derived from postulates and previously proven theorems.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-drafting-compass me-2"></i>Construction</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">Creating geometric figures using compass and straightedge, or digitally with GeoGebra tools.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-ruler-combined me-2"></i>Congruence</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">When two figures have the same shape and size, with corresponding parts equal.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://www.geogebra.org/" target="_blank" class="btn btn-light px-4" style="border-radius: 25px; font-weight: 600;">
                                <i class="fas fa-external-link-alt me-2"></i>Launch GeoGebra
                            </a>
                            <a href="{{ route('teacher.topics.create') }}" class="btn btn-outline-light px-4" style="border-radius: 25px; font-weight: 600; border: 2px solid white;">
                                <i class="fas fa-plus me-2"></i>Create Topic
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-5 p-4" style="background: rgba(0,0,0,0.2);">
                        <h5 class="text-white mb-3" style="font-weight: 600;">
                            <i class="fas fa-play-circle me-2"></i>Tutorial: Getting Started
                        </h5>
                        <div class="ratio ratio-16x9 mb-3" style="border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                            <iframe src="https://www.youtube.com/embed/rEEAu5oAGUg" title="GeoGebra Tutorial - How to Use GeoGebra" allowfullscreen style="border: none;"></iframe>
                        </div>
                        
                        <div class="p-3 mb-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                            <h6 class="text-white mb-2" style="font-weight: 600;"><i class="fas fa-chalkboard-teacher me-2"></i>Teaching Tips</h6>
                            <ul class="text-white mb-0" style="font-size: 0.9rem; opacity: 0.9; line-height: 1.8;">
                                <li>Demonstrate concepts interactively</li>
                                <li>Let students explore and discover</li>
                                <li>Create custom construction activities</li>
                                <li>Share GeoGebra files with students</li>
                            </ul>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('teacher.videos.index') }}" class="btn btn-light btn-sm px-3" style="border-radius: 20px; font-weight: 600;">
                                <i class="fas fa-video me-2"></i>More Video Tutorials
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection