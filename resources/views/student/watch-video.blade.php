@extends('layouts.app')

@section('title', 'Watch Video')

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
<div class="mb-3">
    <a href="{{ route('student.videos') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Videos
    </a>
</div>

<div class="card">
    <div class="card-body">
        <h3 class="mb-3">{{ $video->title }}</h3>
        
        <div class="ratio ratio-16x9 mb-4">
            <video controls class="w-100">
                <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="mb-3">
            <h5>About this video</h5>
            <p class="text-muted">Uploaded by {{ $video->user->name }} on {{ $video->created_at->format('M d, Y') }}</p>
            
            @if($video->description)
                <p>{{ $video->description }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
