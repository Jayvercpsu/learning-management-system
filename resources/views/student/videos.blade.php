@extends('layouts.app')

@section('title', 'Videos')

@section('sidebar')
@include ('student.sidebar')
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
                        <div class="description-container mb-3">
                            @if(strlen($video->description) > 100)
                                <p class="card-text text-muted small mb-1">
                                    <span class="description-short">{{ Str::limit($video->description, 100, '...') }}</span>
                                    <span class="description-full" style="display: none;">{{ $video->description }}</span>
                                </p>
                                <a href="javascript:void(0)" class="read-more-btn text-primary small" style="text-decoration: none; cursor: pointer;">
                                    <i class="fas fa-chevron-down"></i> Read More
                                </a>
                            @else
                                <p class="card-text text-muted small mb-1">{{ $video->description }}</p>
                            @endif
                        </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const readMoreButtons = document.querySelectorAll('.read-more-btn');
    
    readMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.description-container');
            const shortText = container.querySelector('.description-short');
            const fullText = container.querySelector('.description-full');
            const icon = this.querySelector('i');
            
            if (fullText.style.display === 'none') {
                shortText.style.display = 'none';
                fullText.style.display = 'inline';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                this.innerHTML = '<i class="fas fa-chevron-up"></i> Read Less';
            } else {
                shortText.style.display = 'inline';
                fullText.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                this.innerHTML = '<i class="fas fa-chevron-down"></i> Read More';
            }
        });
    });
});
</script>
@endpush