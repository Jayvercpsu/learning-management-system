@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('admin.teachers') }}" class="nav-link">
        <i class="fas fa-chalkboard-teacher"></i> Teachers
    </a>
    <a href="{{ route('admin.students') }}" class="nav-link">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>
@endsection

@section('content')
<h2 class="mb-4">Admin Dashboard</h2>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Teachers</h6>
                        <h2 class="mb-0">{{ $stats['total_teachers'] }}</h2>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Pending Approvals</h6>
                        <h2 class="mb-0">{{ $stats['pending_teachers'] }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Students</h6>
                        <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Topics</h6>
                        <h2 class="mb-0">{{ $stats['total_topics'] }}</h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Videos</h6>
                        <h2 class="mb-0">{{ $stats['total_videos'] }}</h2>
                    </div>
                    <i class="fas fa-video fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Quizzes</h6>
                        <h2 class="mb-0">{{ $stats['total_quizzes'] }}</h2>
                    </div>
                    <i class="fas fa-question-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection