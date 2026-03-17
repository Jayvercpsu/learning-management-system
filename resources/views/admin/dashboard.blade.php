@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar')
@include ('admin.sidebar')
@endsection

@push('styles')
<style>
    .stat-tile {
        border: 1px solid var(--border-color);
        border-radius: 14px;
        background: var(--surface);
        padding: 1rem;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease, color 0.25s ease;
    }

    .stat-tile::before {
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

    .stat-tile > * {
        position: relative;
        z-index: 1;
    }

    .stat-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(37, 99, 235, 0.14);
    }

    .stat-tile:hover::before {
        transform: translateX(0);
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.82rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
    }

    .stat-value {
        font-size: 1.55rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: color-mix(in srgb, var(--accent) 16%, transparent);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h2 class="mb-0">Admin Dashboard</h2>
    <span class="text-muted small">Overview of users and learning resources</span>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-xl-4">
        <div class="stat-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Teachers</div>
                    <p class="stat-value">{{ $stats['total_teachers'] }}</p>
                </div>
                <span class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending Approvals</div>
                    <p class="stat-value">{{ $stats['pending_teachers'] }}</p>
                </div>
                <span class="stat-icon"><i class="fas fa-clock"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Students</div>
                    <p class="stat-value">{{ $stats['total_students'] }}</p>
                </div>
                <span class="stat-icon"><i class="fas fa-user-graduate"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Topics</div>
                    <p class="stat-value">{{ $stats['total_topics'] }}</p>
                </div>
                <span class="stat-icon"><i class="fas fa-book"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Videos</div>
                    <p class="stat-value">{{ $stats['total_videos'] }}</p>
                </div>
                <span class="stat-icon"><i class="fas fa-video"></i></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-4">
        <div class="stat-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Quizzes</div>
                    <p class="stat-value">{{ $stats['total_quizzes'] }}</p>
                </div>
                <span class="stat-icon"><i class="fas fa-question-circle"></i></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-compass me-2 text-primary"></i>Geometry Platform Highlights</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Keep teacher and student workflows focused around clear geometry learning goals.
                </p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-1">Postulate</h6>
                            <p class="text-muted small mb-0">A statement accepted as true and used as a foundation.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-1">Theorem</h6>
                            <p class="text-muted small mb-0">A mathematical statement proven from accepted truths.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-1">Construction</h6>
                            <p class="text-muted small mb-0">Drawing geometric figures using tools or GeoGebra.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-1">Congruence</h6>
                            <p class="text-muted small mb-0">Figures with equal shape and size in all parts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-bolt me-2 text-primary"></i>Quick Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.teachers') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Manage Teachers
                </a>
                <a href="{{ route('admin.students') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-graduate me-2"></i>Manage Students
                </a>
                <a href="https://www.geogebra.org/" target="_blank" class="btn btn-outline-secondary">
                    <i class="fas fa-external-link-alt me-2"></i>Open GeoGebra
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0"><i class="fas fa-play-circle me-2 text-primary"></i>Platform Tutorial</h6>
            </div>
            <div class="card-body">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/rEEAu5oAGUg" title="GeoGebra Tutorial - How to Use GeoGebra" allowfullscreen style="border: 0;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
