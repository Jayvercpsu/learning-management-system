@extends('layouts.app')

@section('title', 'Take Quiz')

@section('sidebar')
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
    <a href="{{ route('student.quizzes') }}" class="nav-link active">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('student.quizzes.results') }}" class="nav-link">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $quiz->title }}</h4>
            @if($quiz->duration)
                <div id="timer" class="badge bg-danger fs-5">
                    <i class="fas fa-clock"></i> <span id="time-remaining">{{ $quiz->duration }}:00</span>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($quiz->description)
            <div class="alert alert-info">
                {{ $quiz->description }}
            </div>
        @endif

        <form action="{{ route('student.quizzes.submit', $quiz) }}" method="POST" id="quizForm">
            @csrf

            @foreach($quiz->questions as $index => $question)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                            <span class="badge bg-primary">{{ $question->points }} {{ Str::plural('point', $question->points) }}</span>
                        </div>

                        <p class="mb-3">{{ $question->question }}</p>

                        @if($question->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $question->image) }}" alt="Question image" class="img-fluid" style="max-height: 300px;">
                            </div>
                        @endif

                        @if($question->type === 'multiple_choice')
                            <div class="list-group">
                                @foreach($question->options as $option)
                                    <label class="list-group-item">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" class="form-check-input me-2" required>
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                        @elseif($question->type === 'true_false')
                            <div class="list-group">
                                <label class="list-group-item">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="True" class="form-check-input me-2" required>
                                    True
                                </label>
                                <label class="list-group-item">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="False" class="form-check-input me-2" required>
                                    False
                                </label>
                            </div>
                        @elseif($question->type === 'essay')
                            <textarea name="answers[{{ $question->id }}]" class="form-control" rows="5" placeholder="Type your answer here..." required></textarea>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between">
                <a href="{{ route('student.quizzes') }}" class="btn btn-secondary" onclick="return confirm('Are you sure you want to leave? Your progress will be lost.')">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to submit? You cannot change your answers after submission.')">
                    <i class="fas fa-check"></i> Submit Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if($quiz->duration)
<script>
    let timeRemaining = {{ $quiz->duration * 60 }};
    const timerElement = document.getElementById('time-remaining');
    const form = document.getElementById('quizForm');

    const countdown = setInterval(function() {
        timeRemaining--;
        
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeRemaining <= 60) {
            document.getElementById('timer').classList.remove('bg-danger');
            document.getElementById('timer').classList.add('bg-warning');
        }
        
        if (timeRemaining <= 0) {
            clearInterval(countdown);
            alert('Time is up! The quiz will be submitted automatically.');
            form.submit();
        }
    }, 1000);

    form.addEventListener('submit', function() {
        clearInterval(countdown);
    });
</script>
@endif
@endpush

