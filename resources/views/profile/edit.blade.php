@extends('layouts.app')

@section('title', 'Edit Profile')

@section('sidebar')
@if(auth()->user()->isAdmin())
    <nav class="nav flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fas fa-dashboard"></i> Dashboard
        </a>
        <a href="{{ route('admin.teachers') }}" class="nav-link">
            <i class="fas fa-chalkboard-teacher"></i> Teachers
        </a>
        <a href="{{ route('admin.students') }}" class="nav-link">
            <i class="fas fa-user-graduate"></i> Students
        </a>
    </nav>
@elseif(auth()->user()->isTeacher())
    <nav class="nav flex-column">
        <a href="{{ route('teacher.dashboard') }}" class="nav-link">
            <i class="fas fa-dashboard"></i> Dashboard
        </a>
        <a href="{{ route('teacher.topics.index') }}" class="nav-link">
            <i class="fas fa-book"></i> Topics
        </a>
        <a href="{{ route('teacher.videos.index') }}" class="nav-link">
            <i class="fas fa-video"></i> Videos
        </a>
        <a href="{{ route('teacher.quizzes.index') }}" class="nav-link">
            <i class="fas fa-question-circle"></i> Quizzes
        </a>
        <a href="{{ route('teacher.students') }}" class="nav-link">
            <i class="fas fa-user-graduate"></i> Students
        </a>
    </nav>
@else
    <nav class="nav flex-column">
        <a href="{{ route('student.dashboard') }}" class="nav-link">
            <i class="fas fa-dashboard"></i> Dashboard
        </a>
        <a href="{{ route('student.topics') }}" class="nav-link">
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
@endif
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Edit Profile</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="rounded-circle mb-3" id="profilePreview" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white mb-3" id="profilePlaceholder" style="width: 150px; height: 150px; font-size: 48px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img src="" alt="Profile" class="rounded-circle mb-3 d-none" id="profilePreview" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*" id="profileInput">
                        <small class="text-muted">Upload a profile picture (Max: 2MB)</small>
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

<div class="mb-3">
    <label class="form-label">Bio</label>
    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4">{{ old('bio', $user->bio) }}</textarea>
    @error('bio')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@if($user->isStudent())
    <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id', $user->student_id) }}">
        @error('student_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Course</label>
        <input type="text" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course', $user->course) }}">
        @error('course')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Section</label>
        <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" value="{{ old('section', $user->section) }}">
        @error('section')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

                    <hr class="my-4">

                    <h5 class="mb-3">Change Password (Optional)</h5>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        <small class="text-muted">Leave blank to keep current password</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
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
    document.getElementById('profileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                const placeholder = document.getElementById('profilePlaceholder');
                
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush