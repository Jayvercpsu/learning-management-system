@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@push('styles')
<style>
    .dashboard-stat {
        border: 1px solid var(--border-color);
        border-radius: 14px;
        background: var(--surface);
        padding: 1rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .dashboard-stat::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            90deg,
            color-mix(in srgb, var(--accent) 10%, transparent) 0%,
            color-mix(in srgb, var(--accent) 20%, transparent) 100%
        );
        transform: translateX(-105%);
        transition: transform 0.35s ease;
        z-index: 0;
    }

    .dashboard-stat > * {
        position: relative;
        z-index: 1;
    }

    .dashboard-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(37, 99, 235, 0.14);
    }

    .dashboard-stat:hover::before {
        transform: translateX(0);
    }

    .dashboard-stat small {
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 0.78rem;
    }

    .dashboard-stat h3 {
        margin: 0.3rem 0 0;
        font-weight: 700;
    }

    .recent-item {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.8rem;
        margin-bottom: 0.7rem;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .recent-item:hover {
        background: var(--surface-soft);
        transform: translateX(2px);
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h2 class="mb-0">Teacher Dashboard</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.topics.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>New Topic
        </a>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-pen me-1"></i>New Quiz
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3">
        <div class="dashboard-stat">
            <small>My Topics</small>
            <h3>{{ $stats['total_topics'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="dashboard-stat">
            <small>My Videos</small>
            <h3>{{ $stats['total_videos'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="dashboard-stat">
            <small>My Quizzes</small>
            <h3>{{ $stats['total_quizzes'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="dashboard-stat">
            <small>Total Students</small>
            <h3>{{ $stats['total_students'] }}</h3>
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
                    <div class="recent-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $topic->title }}</h6>
                                <small class="text-muted">{{ $topic->created_at->diffForHumans() }}</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No topics uploaded yet.</p>
                @endforelse
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
                    <div class="recent-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $video->title }}</h6>
                                <small class="text-muted">{{ $video->created_at->diffForHumans() }}</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No videos uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Teaching Notes</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Use GeoGebra to demonstrate geometric concepts step-by-step, then reinforce with topics and quizzes.
                </p>
                <ul class="mb-0 text-muted">
                    <li>Start with a visual construction demo.</li>
                    <li>Upload supporting lesson topics and videos.</li>
                    <li>Use quizzes to check understanding and track progress.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0"><i class="fas fa-play-circle me-2 text-primary"></i>GeoGebra Tutorial</h6>
            </div>
            <div class="card-body">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/rEEAu5oAGUg" title="GeoGebra Tutorial - How to Use GeoGebra" allowfullscreen style="border: 0;"></iframe>
                </div>
                <a href="https://www.geogebra.org/" target="_blank" class="btn btn-outline-primary btn-sm w-100 mt-3">
                    <i class="fas fa-external-link-alt me-1"></i>Open GeoGebra
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
