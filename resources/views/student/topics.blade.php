@extends('layouts.app')

@section('title', 'Topics')

@section('sidebar')
@include ('student.sidebar')
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
                        <div class="description-container mb-3">
                            @if(strlen($topic->description) > 100)
                                <p class="card-text text-muted small mb-1">
                                    <span class="description-short">{{ Str::limit($topic->description, 100, '...') }}</span>
                                    <span class="description-full" style="display: none;">{{ $topic->description }}</span>
                                </p>
                                <a href="javascript:void(0)" class="read-more-btn text-primary small" style="text-decoration: none; cursor: pointer;">
                                    <i class="fas fa-chevron-down"></i> Read More
                                </a>
                            @else
                                <p class="card-text text-muted small mb-1">{{ $topic->description }}</p>
                            @endif
                        </div>
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