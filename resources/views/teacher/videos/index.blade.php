@extends('layouts.app')

@section('title', 'My Videos')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Videos</h2>
    <a href="{{ route('teacher.videos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Upload New Video
    </a>
</div>

<div class="row g-4">
    @forelse($videos as $video)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">{{ $video->title }}</h5>
                    <p class="text-muted small mb-3">{{ $video->created_at->format('M d, Y') }}</p>
                    
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
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge bg-success">
                            <i class="fas fa-play"></i> Video
                        </span>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $video->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('modals.delete-modal-video')

    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No videos uploaded yet. Click "Upload New Video" to add one.
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