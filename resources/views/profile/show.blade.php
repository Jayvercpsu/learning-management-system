@extends('layouts.app')

@section('title', 'My Profile')

@section('sidebar')
@if(auth()->user()->isAdmin())
    <nav class="nav flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-dashboard"></i> Dashboard
        </a>
        <a href="{{ route('admin.teachers') }}" class="nav-link">
            <i class="fas fa-chalkboard-teacher"></i> Teachers
        </a>
        <a href="{{ route('admin.students') }}" class="nav-link">
            <i class="fas fa-user-graduate"></i> Students
        </a>
    </nav>
@elseif(auth()->user()->isTeacher())
    <nav class="nav flex-column">
        <a href="{{ route('teacher.dashboard') }}" class="nav-link">
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
@else
    <nav class="nav flex-column">
        <a href="{{ route('student.dashboard') }}" class="nav-link">
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
@endif
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">My Profile</h4>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white" style="width: 150px; height: 150px; font-size: 48px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Full Name</div>
                    <div class="col-md-8"><strong>{{ $user->name }}</strong></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Email</div>
                    <div class="col-md-8"><strong>{{ $user->email }}</strong></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Role</div>
                    <div class="col-md-8">
                        <span class="badge bg-primary">{{ strtoupper($user->role) }}</span>
                    </div>
                </div>

                @if($user->phone)
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Phone</div>
                        <div class="col-md-8"><strong>{{ $user->phone }}</strong></div>
                    </div>
                @endif

                @if($user->bio)
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Bio</div>
                        <div class="col-md-8">{{ $user->bio }}</div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Member Since</div>
                    <div class="col-md-8"><strong>{{ $user->created_at->format('F d, Y') }}</strong></div>
                </div>

                @if($user->isTeacher())
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted">Account Status</div>
                        <div class="col-md-8">
                            @if($user->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending Approval</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection