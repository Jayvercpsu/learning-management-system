@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('sidebar')
@include ('student.sidebar')
@endsection

@push('styles')
<style>
    .student-overview {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 1rem;
    }

    .student-stat {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 0.95rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .student-stat::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, #eff6ff 0%, #dbeafe 100%);
        transform: translateX(-105%);
        transition: transform 0.35s ease;
        z-index: 0;
    }

    .student-stat > * {
        position: relative;
        z-index: 1;
    }

    .student-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(37, 99, 235, 0.14);
    }

    .student-stat:hover::before {
        transform: translateX(0);
    }

    .student-stat small {
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 0.78rem;
    }

    .student-stat h3 {
        margin: 0.3rem 0 0;
        font-weight: 700;
    }

    .recent-resource {
        border: 1px solid #edf0f3;
        border-radius: 10px;
        padding: 0.8rem;
        margin-bottom: 0.7rem;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .recent-resource:hover {
        background: #f8fafc;
        transform: translateX(2px);
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h2 class="mb-0">Welcome, {{ auth()->user()->name }}!</h2>
    <a href="https://www.geogebra.org/" target="_blank" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-external-link-alt me-1"></i>Open GeoGebra
    </a>
</div>

<div class="student-overview mb-3">
    <div class="row g-3 align-items-center">
        <div class="col-lg-8">
            <h5 class="mb-2">Learn Geometry with Interactive Tools</h5>
            <p class="text-muted mb-0">
                Review uploaded topics and videos, then take quizzes to measure your progress.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="{{ route('student.topics') }}" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-book me-1"></i>Topics
            </a>
            <a href="{{ route('student.quizzes') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-pen me-1"></i>Quizzes
            </a>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="student-stat">
            <small>Available Topics</small>
            <h3>{{ $stats['available_topics'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="student-stat">
            <small>Available Videos</small>
            <h3>{{ $stats['available_videos'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="student-stat">
            <small>Available Quizzes</small>
            <h3>{{ $stats['available_quizzes'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="student-stat">
            <small>Completed Quizzes</small>
            <h3>{{ $stats['completed_quizzes'] }}</h3>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-book me-2 text-primary"></i>Recent Topics</h5>
            </div>
            <div class="card-body">
                @forelse($recentTopics as $topic)
                    <div class="recent-resource">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $topic->title }}</h6>
                                <small class="text-muted">{{ $topic->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('student.topics.download', $topic) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No topics available yet.</p>
                @endforelse

                @if($recentTopics->count() > 0)
                    <a href="{{ route('student.topics') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">View All Topics</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-video me-2 text-primary"></i>Recent Videos</h5>
            </div>
            <div class="card-body">
                @forelse($recentVideos as $video)
                    <div class="recent-resource">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $video->title }}</h6>
                                <small class="text-muted">{{ $video->created_at->diffForHumans() }}</small>
                            </div>
                            <a href="{{ route('student.videos.watch', $video) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No videos available yet.</p>
                @endforelse

                @if($recentVideos->count() > 0)
                    <a href="{{ route('student.videos') }}" class="btn btn-outline-primary btn-sm w-100 mt-2">View All Videos</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
