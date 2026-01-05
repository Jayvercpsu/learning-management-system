@extends('layouts.app')

@section('title', 'Upload Video')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Upload New Video</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.videos.store') }}" method="POST" enctype="multipart/form-data" id="videoUploadForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Video Title</label>
                        <input type="text" name="title" id="videoTitle" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" id="videoDescription" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Video</label>
                        <input type="file" name="video" id="videoFile" class="form-control @error('video') is-invalid @enderror" accept=".mp4,.mov,.avi,.wmv" required>
                        <small class="text-muted">Supported: MP4, MOV, AVI, WMV (Max: 500MB)</small>
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div id="fileInfo" class="mt-2 text-muted" style="display: none;">
                            <i class="fas fa-file-video"></i> 
                            <span id="fileName"></span> - 
                            <span id="fileSize"></span>
                        </div>
                    </div>

                    <!-- Upload Progress Section -->
                    <div id="uploadProgress" style="display: none;">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">
                                    <i class="fas fa-cloud-upload-alt"></i> Uploading Video...
                                </h6>
                                <div class="progress mb-2" style="height: 25px;">
                                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                         role="progressbar" 
                                         style="width: 0%;" 
                                         aria-valuenow="0" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <span id="progressText">0%</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span id="uploadedSize">0 MB</span>
                                    <span id="uploadSpeed">0 KB/s</span>
                                    <span id="timeRemaining">Calculating...</span>
                                </div>
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle"></i> Please don't close or refresh this page while uploading.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div id="uploadSuccess" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle"></i> Video uploaded successfully! Redirecting...
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.videos.index') }}" class="btn btn-secondary" id="backBtn">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                            <i class="fas fa-upload"></i> Upload Video
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('videoUploadForm');
    const fileInput = document.getElementById('videoFile');
    const uploadBtn = document.getElementById('uploadBtn');
    const backBtn = document.getElementById('backBtn');
    const progressSection = document.getElementById('uploadProgress');
    const successSection = document.getElementById('uploadSuccess');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadedSize = document.getElementById('uploadedSize');
    const uploadSpeed = document.getElementById('uploadSpeed');
    const timeRemaining = document.getElementById('timeRemaining');
    
    let isUploading = false;

    // Show file info when file is selected (NO AUTO-UPLOAD)
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
            const maxSize = 500;
            
            fileName.textContent = file.name;
            fileSize.textContent = fileSizeMB + ' MB';
            fileInfo.style.display = 'block';
            
            if (fileSizeMB > maxSize) {
                alert(`File size is ${fileSizeMB}MB. Maximum allowed size is ${maxSize}MB. Please choose a smaller file.`);
                fileInput.value = '';
                fileInfo.style.display = 'none';
                return false;
            }
            
            // Just show file info, DO NOT upload yet
            console.log('File selected:', file.name);
        } else {
            fileInfo.style.display = 'none';
        }
    });

    // Handle form submission ONLY when button is clicked
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const formData = new FormData(form);
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Please select a video file to upload.');
            return;
        }

 
        // Set uploading flag
        isUploading = true;

        // Show progress section
        progressSection.style.display = 'block';
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        backBtn.classList.add('disabled');
        backBtn.style.pointerEvents = 'none';
        fileInput.disabled = true;
        document.getElementById('videoTitle').disabled = true;
        document.getElementById('videoDescription').disabled = true;

        // Track upload timing
        let startTime = Date.now();
        let previousLoaded = 0;

        // Create XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();

        // Upload progress event
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                const loadedMB = (e.loaded / 1024 / 1024).toFixed(2);
                const totalMB = (e.total / 1024 / 1024).toFixed(2);
                
                // Update progress bar
                progressBar.style.width = percentComplete + '%';
                progressBar.setAttribute('aria-valuenow', percentComplete);
                progressText.textContent = percentComplete + '%';
                uploadedSize.textContent = loadedMB + ' MB / ' + totalMB + ' MB';

                // Calculate upload speed
                const currentTime = Date.now();
                const elapsedTime = (currentTime - startTime) / 1000;
                
                if (elapsedTime > 0) {
                    const uploadedBytes = e.loaded - previousLoaded;
                    const speedBps = uploadedBytes / elapsedTime;
                    const speedKBps = (speedBps / 1024).toFixed(2);
                    
                    uploadSpeed.textContent = speedKBps + ' KB/s';
                    previousLoaded = e.loaded;
                    startTime = currentTime;

                    // Calculate time remaining
                    if (speedBps > 0) {
                        const remainingBytes = e.total - e.loaded;
                        const remainingSeconds = Math.round(remainingBytes / speedBps);
                        
                        if (remainingSeconds < 60) {
                            timeRemaining.textContent = remainingSeconds + ' seconds remaining';
                        } else {
                            const minutes = Math.floor(remainingSeconds / 60);
                            const seconds = remainingSeconds % 60;
                            timeRemaining.textContent = minutes + 'm ' + seconds + 's remaining';
                        }
                    }
                } else {
                    timeRemaining.textContent = 'Calculating...';
                }

                // Change color based on progress
                if (percentComplete < 50) {
                    progressBar.classList.remove('bg-warning', 'bg-success');
                    progressBar.classList.add('bg-info');
                } else if (percentComplete < 90) {
                    progressBar.classList.remove('bg-info', 'bg-success');
                    progressBar.classList.add('bg-warning');
                } else {
                    progressBar.classList.remove('bg-info', 'bg-warning');
                    progressBar.classList.add('bg-success');
                }
            }
        });

        // Upload complete
        xhr.addEventListener('load', function() {
            if (xhr.status === 200 || xhr.status === 302) {
                progressText.textContent = '100% - Complete!';
                progressBar.classList.remove('progress-bar-animated');
                progressBar.classList.add('bg-success');
                
                // Upload finished - allow navigation
                isUploading = false;
                
                // Show success message
                progressSection.style.display = 'none';
                successSection.style.display = 'block';
                
                // Redirect after 2 seconds
                setTimeout(function() {
                    window.location.href = "{{ route('teacher.videos.index') }}";
                }, 2000);
            } else {
                isUploading = false;
                alert('Upload failed. Please try again.');
                resetForm();
            }
        });

        // Upload error
        xhr.addEventListener('error', function() {
            isUploading = false;
            alert('An error occurred during upload. Please try again.');
            resetForm();
        });

        // Send the request
        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.send(formData);
    });

    function resetForm() {
        progressSection.style.display = 'none';
        successSection.style.display = 'none';
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload Video';
        backBtn.classList.remove('disabled');
        backBtn.style.pointerEvents = 'auto';
        fileInput.disabled = false;
        document.getElementById('videoTitle').disabled = false;
        document.getElementById('videoDescription').disabled = false;
        progressBar.style.width = '0%';
        progressText.textContent = '0%';
    }

    // Prevent accidental page leave ONLY during upload
    window.addEventListener('beforeunload', function(e) {
        if (isUploading) {
            e.preventDefault();
            e.returnValue = 'Upload in progress. Are you sure you want to leave?';
            return 'Upload in progress. Are you sure you want to leave?';
        }
    });
});
</script>
@endpush