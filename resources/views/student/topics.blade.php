@extends('layouts.app')

@section('title', 'Topics')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('student.topics') }}" class="nav-link active">
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
@endsection

@section('content')
<h2 class="mb-4">Available Topics</h2>

<div class="row g-4">
    @forelse($topics as $topic)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{{ $topic->title }}</h5>
                            <small class="text-muted">By {{ $topic->user->name }}</small>
                        </div>
                        <span class="badge bg-primary">{{ strtoupper($topic->file_type) }}</span>
                    </div>
                    
                    @if($topic->description)
                        <p class="card-text text-muted small mb-3">{{ Str::limit($topic->description, 100) }}</p>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-file"></i> {{ $topic->getFileSizeFormatted() }}
                        </small>
                        <a href="{{ route('student.topics.download', $topic) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No topics available yet.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>
@endsection
