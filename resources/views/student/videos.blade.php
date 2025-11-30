@extends('layouts.app')

@section('title', 'Videos')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('student.topics') }}" class="nav-link">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('student.videos') }}" class="nav-link active">
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
<h2 class="mb-4">Video Tutorials</h2>

<div class="row g-4">
    @forelse($videos as $video)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">{{ $video->title }}</h5>
                    <small class="text-muted d-block mb-3">By {{ $video->user->name }}</small>
                    
                    @if($video->description)
                        <p class="card-text text-muted small mb-3">{{ Str::limit($video->description, 100) }}</p>
                    @endif
                    
                    <a href="{{ route('student.videos.watch', $video) }}" class="btn btn-success w-100">
                        <i class="fas fa-play"></i> Watch Video
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No videos available yet.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $videos->links() }}
</div>
@endsection