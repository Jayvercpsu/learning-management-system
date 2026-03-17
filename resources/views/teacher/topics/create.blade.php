@extends('layouts.app')

@section('title', 'Upload Topic')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@section('content')
@php
    $topicExtensions = array_values(array_unique(array_map(
        static fn (string $extension): string => strtolower(ltrim($extension, '.')),
        (array) config('media.topic_upload.extensions', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'jpg', 'jpeg', 'png', 'gif'])
    )));
    $topicAccept = implode(',', array_map(static fn (string $extension): string => '.' . $extension, $topicExtensions));
    $topicConfiguredMaxMb = (int) ($topicConfiguredMaxMb ?? ceil(((int) config('media.topic_upload.max_kb', 204800)) / 1024));
    $topicEffectiveMaxMb = (int) ($topicEffectiveMaxMb ?? $topicConfiguredMaxMb);
    $phpUploadMax = (string) ($phpUploadMax ?? ini_get('upload_max_filesize'));
    $phpPostMax = (string) ($phpPostMax ?? ini_get('post_max_size'));
@endphp
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Upload New Topic</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.topics.store') }}" method="POST" enctype="multipart/form-data" id="topicUploadForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Topic Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input type="file" name="file" id="topicFile" class="form-control @error('file') is-invalid @enderror" accept="{{ $topicAccept }}" required>
                        <small class="text-muted">Supported: PDF, Word, PowerPoint, Excel, CSV, Images</small>
                        {{-- <div class="small text-muted mt-1">Server limits now: upload_max_filesize={{ $phpUploadMax }}, post_max_size={{ $phpPostMax }}</div> --}}
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.topics.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary" data-loading-text="Uploading Topic...">
                            <i class="fas fa-upload"></i> Upload Topic
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('topicUploadForm');
    const fileInput = document.getElementById('topicFile');
    const maxSizeMB = {{ $topicEffectiveMaxMb }};
    const allowedExtensions = @json($topicExtensions);

    console.log('[TopicUpload] Ready', {
        maxSizeMB,
        allowedExtensions,
        phpUploadMax: @json($phpUploadMax),
        phpPostMax: @json($phpPostMax),
    });

    if (!form || !fileInput) {
        console.error('[TopicUpload] Form or file input not found');
        return;
    }

    if (maxSizeMB < 50) {
        console.error('[TopicUpload] Server limit is currently too low for 50MB+ uploads', {
            maxSizeMB,
            phpUploadMax: @json($phpUploadMax),
            phpPostMax: @json($phpPostMax),
            fix: 'Increase php.ini limits or run: php -d upload_max_filesize=512M -d post_max_size=512M artisan serve',
        });
    }

    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) {
            console.log('[TopicUpload] No file selected');
            return;
        }

        const extension = (file.name.split('.').pop() || '').toLowerCase();
        const sizeMB = Number((file.size / (1024 * 1024)).toFixed(2));
        const isAllowedExtension = allowedExtensions.includes(extension);

        console.log('[TopicUpload] File selected', {
            name: file.name,
            sizeMB,
            type: file.type || '(empty)',
            extension,
            isAllowedExtension,
            maxSizeMB,
        });

        if (!isAllowedExtension) {
            console.error('[TopicUpload] Blocked: invalid extension', { extension, allowedExtensions });
        }

        if (sizeMB > maxSizeMB) {
            console.error('[TopicUpload] File is larger than current effective server limit. Upload will likely fail server-side.', {
                sizeMB,
                maxSizeMB,
                phpUploadMax: @json($phpUploadMax),
                phpPostMax: @json($phpPostMax),
            });
        }
    });

    form.addEventListener('submit', function () {
        const file = fileInput.files[0];
        if (!file) {
            console.error('[TopicUpload] Submit blocked: no file selected');
            return;
        }

        console.log('[TopicUpload] Submitting form', {
            action: form.action,
            method: form.method,
            fileName: file.name,
            sizeMB: Number((file.size / (1024 * 1024)).toFixed(2)),
            type: file.type || '(empty)',
        });
    });

    @if ($errors->has('file'))
    console.error('[TopicUpload] Last server validation error (file)', {
        error: @json($errors->first('file')),
        phpUploadMax: @json($phpUploadMax),
        phpPostMax: @json($phpPostMax),
    });
    @endif

    @if (session('error'))
    console.error('[TopicUpload] Last server error', {
        error: @json(session('error')),
        phpUploadMax: @json($phpUploadMax),
        phpPostMax: @json($phpPostMax),
    });
    @endif
});
</script>
@endpush
