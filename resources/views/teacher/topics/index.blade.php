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
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary flex-fill view-topic-btn"
                            data-topic-id="{{ $topic->id }}"
                            data-topic-title="{{ $topic->title }}"
                            data-topic-url="{{ $topic->file_url }}"
                            data-topic-type="{{ $topic->file_type }}"
                            data-topic-is-office="{{ $topic->is_office_file ? '1' : '0' }}"
                            data-topic-preview-url="{{ $topic->office_preview_url }}"
                            data-topic-preview-fallback-url="{{ $topic->office_preview_fallback_url }}"
                        >
                            <i class="fas fa-eye"></i> {{ $topic->is_office_file ? 'View File' : 'View' }}
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

    function toSafeHttpUrl(rawUrl) {
        if (!rawUrl) {
            return null;
        }

        try {
            const parsed = new URL(rawUrl, window.location.origin);
            if (!['http:', 'https:'].includes(parsed.protocol)) {
                return null;
            }

            return parsed.toString();
        } catch (error) {
            return null;
        }
    }

    function normalizeLocalStorageOrigin(rawUrl) {
        const safeUrl = toSafeHttpUrl(rawUrl);
        if (!safeUrl) {
            return null;
        }

        try {
            const parsed = new URL(safeUrl);
            const current = new URL(window.location.href);
            const isStoragePath = parsed.pathname.startsWith('/storage/');
            const localHosts = ['localhost', '127.0.0.1', '::1'];
            const isParsedLocal = localHosts.includes(parsed.hostname);
            const isCurrentLocal = localHosts.includes(current.hostname);

            if (isStoragePath && isParsedLocal && isCurrentLocal) {
                return `${current.origin}${parsed.pathname}${parsed.search}${parsed.hash}`;
            }

            return parsed.toString();
        } catch (error) {
            return safeUrl;
        }
    }

    function renderOfficePreview(primaryViewerUrl, fallbackViewerUrl, sourceFileUrl) {
        const safePrimaryViewerUrl = toSafeHttpUrl(primaryViewerUrl);
        const safeFallbackViewerUrl = toSafeHttpUrl(fallbackViewerUrl);
        const safeSourceFileUrl = toSafeHttpUrl(sourceFileUrl);

        if (!safePrimaryViewerUrl) {
            fileViewer.innerHTML = `
                <div class="alert alert-warning m-4">
                    <div class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Preview is not available for this file URL.</div>
                    ${safeSourceFileUrl ? `
                        <a href="${safeSourceFileUrl}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                            <i class="fas fa-download me-1"></i>Download / Open File
                        </a>
                    ` : ''}
                </div>
            `;
            return;
        }

        fileViewer.innerHTML = `
            <div class="w-100 h-100 d-flex flex-column">
                <div class="border-bottom p-2 d-flex gap-2 justify-content-end">
                    ${safeFallbackViewerUrl ? '<button type="button" class="btn btn-outline-secondary btn-sm" id="useGoogleViewerBtn"><i class="fas fa-exchange-alt me-1"></i>Try Google Viewer</button>' : ''}
                    ${safeSourceFileUrl ? `<a href="${safeSourceFileUrl}" target="_blank" rel="noopener" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt me-1"></i>Open File</a>` : ''}
                </div>
                <iframe id="officePreviewFrame" src="${safePrimaryViewerUrl}" class="w-100 flex-grow-1" style="border: none;"></iframe>
            </div>
        `;

        const frame = document.getElementById('officePreviewFrame');
        const googleBtn = document.getElementById('useGoogleViewerBtn');
        if (frame && googleBtn && safeFallbackViewerUrl) {
            googleBtn.addEventListener('click', function () {
                frame.src = safeFallbackViewerUrl;
                googleBtn.disabled = true;
                googleBtn.innerHTML = '<i class="fas fa-check me-1"></i>Using Google Viewer';
            });
        }
    }
    
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const topicTitle = this.getAttribute('data-topic-title');
            const topicUrlRaw = this.getAttribute('data-topic-url');
            const topicUrl = normalizeLocalStorageOrigin(topicUrlRaw);
            const topicType = (this.getAttribute('data-topic-type') || '').toLowerCase();
            const isOfficeFile = this.getAttribute('data-topic-is-office') === '1';
            const officePreviewUrl = this.getAttribute('data-topic-preview-url');
            const officePreviewFallbackUrl = this.getAttribute('data-topic-preview-fallback-url');

            modalTitle.textContent = topicTitle;


            fileViewer.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';

            modal.show();

            setTimeout(() => {
                if (!topicUrl) {
                    console.warn('[TopicPreview] Invalid topic URL', { topicTitle, topicUrlRaw, topicType });
                    fileViewer.innerHTML = '<div class="alert alert-warning m-4">Invalid file URL. Please re-upload this file.</div>';
                    return;
                }

                if (topicType === 'pdf') {
                    fileViewer.innerHTML = `
                        <object data="${topicUrl}" type="application/pdf" class="w-100 h-100">
                            <embed src="${topicUrl}" type="application/pdf" class="w-100 h-100" />
                            <div class="p-4 text-center">
                                <p class="mb-3">PDF preview is not supported on this device.</p>
                                <a href="${topicUrl}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>Open PDF
                                </a>
                            </div>
                        </object>
                    `;
                } else if (isOfficeFile) {
                    renderOfficePreview(officePreviewUrl, officePreviewFallbackUrl, topicUrl);
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
