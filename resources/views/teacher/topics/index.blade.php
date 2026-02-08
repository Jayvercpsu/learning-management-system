@extends('layouts.app')

@section('title', 'My Topics')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Topics</h2>
    <a href="{{ route('teacher.topics.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Upload New Topic
    </a>
</div>

<div class="row g-4">
    @forelse($topics as $topic)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{{ $topic->title }}</h5>
                            <small class="text-muted">{{ $topic->created_at->format('M d, Y') }}</small>
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
                    
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-file"></i> {{ $topic->getFileSizeFormatted() }}
                        </small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary flex-fill view-topic-btn" data-topic-id="{{ $topic->id }}" data-topic-title="{{ $topic->title }}" data-topic-url="{{ asset('storage/' . $topic->file_path) }}" data-topic-type="{{ $topic->file_type }}">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $topic->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('modals.delete-modal-topic')

    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No topics uploaded yet. Click "Upload New Topic" to add one.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $topics->links() }}
</div>

<div class="modal fade" id="viewTopicModal" tabindex="-1" aria-labelledby="viewTopicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTopicModalLabel">
                    <i class="fas fa-file-alt me-2"></i><span id="modalTopicTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <div id="fileViewer" class="w-100 h-100 d-flex align-items-center justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
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

    const viewButtons = document.querySelectorAll('.view-topic-btn');
    const modal = new bootstrap.Modal(document.getElementById('viewTopicModal'));
    const fileViewer = document.getElementById('fileViewer');
    const modalTitle = document.getElementById('modalTopicTitle');
    
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const topicTitle = this.getAttribute('data-topic-title');
            const topicUrl = this.getAttribute('data-topic-url');
            const topicType = this.getAttribute('data-topic-type');
            
            modalTitle.textContent = topicTitle;
            
            
            fileViewer.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
            
            modal.show();
            
            setTimeout(() => {
                if (topicType === 'pdf') {
                    fileViewer.innerHTML = `<iframe src="${topicUrl}" class="w-100 h-100" style="border: none;"></iframe>`;
                } else if (['doc', 'docx'].includes(topicType)) {
                    fileViewer.innerHTML = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(topicUrl)}" class="w-100 h-100" style="border: none;"></iframe>`;
                } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(topicType)) {
                    fileViewer.innerHTML = `<img src="${topicUrl}" class="img-fluid" alt="${topicTitle}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                } else if (['txt', 'md'].includes(topicType)) {
                    fetch(topicUrl)
                        .then(response => response.text())
                        .then(text => {
                            fileViewer.innerHTML = `<div class="p-4 bg-light h-100 overflow-auto"><pre class="mb-0">${text}</pre></div>`;
                        })
                        .catch(error => {
                            fileViewer.innerHTML = `<div class="alert alert-danger m-4">Error loading file. Please download to view.</div>`;
                        });
                } else {
                    fileViewer.innerHTML = `<div class="alert alert-info m-4"><i class="fas fa-info-circle me-2"></i>Preview not available for this file type. Please download to view.</div>`;
                }
            }, 100);
        });
    });
    
    document.getElementById('viewTopicModal').addEventListener('hidden.bs.modal', function() {
        fileViewer.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
    });
});
</script>
@endpush