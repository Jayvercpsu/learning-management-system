@extends('layouts.app')

@section('title', 'Create Quiz')

@section('sidebar')
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
    <a href="{{ route('teacher.quizzes.index') }}" class="nav-link active">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('teacher.students') }}" class="nav-link">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Create New Quiz</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('teacher.quizzes.store') }}" method="POST" enctype="multipart/form-data" id="quizForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quiz Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration (minutes, optional)</label>
                    <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" min="1">
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description (Optional)</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Passing Score (%)</label>
                    <input type="number" name="passing_score" class="form-control @error('passing_score') is-invalid @enderror" value="{{ old('passing_score', 50) }}" min="0" max="100" required>
                    @error('passing_score')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Grading Type</label>
                    <select name="auto_check" class="form-select @error('auto_check') is-invalid @enderror" required>
                        <option value="1" {{ old('auto_check') == '1' ? 'selected' : '' }}>Auto-check (Multiple Choice & True/False)</option>
                        <option value="0" {{ old('auto_check') == '0' ? 'selected' : '' }}>Manual Grading</option>
                    </select>
                    @error('auto_check')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Quiz Questions</h5>

            <div id="questionsContainer">
                <div class="question-item card mb-3" data-index="0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Question 1</h6>
                            <button type="button" class="btn btn-sm btn-danger remove-question" style="display:none;">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <textarea name="questions[0][question]" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Question Type</label>
                                <select name="questions[0][type]" class="form-select question-type" required>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="true_false">True/False</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Points</label>
                                <input type="number" name="questions[0][points]" class="form-control" value="1" min="1" required>
                            </div>
                        </div>

                        <div class="options-container">
                            <label class="form-label">Options (one per line)</label>
                            <textarea name="questions[0][options]" class="form-control mb-2" rows="4" placeholder="Option A&#10;Option B&#10;Option C&#10;Option D"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correct Answer</label>
                            <input type="text" name="questions[0][correct_answer]" class="form-control" placeholder="Enter the correct option">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image (Optional)</label>
                            <input type="file" name="questions[0][image]" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-4" id="addQuestion">
                <i class="fas fa-plus"></i> Add Another Question
            </button>

            <div class="d-flex justify-content-between">
                <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Quiz
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let questionCount = 1;

document.getElementById('addQuestion').addEventListener('click', function() {
    const container = document.getElementById('questionsContainer');
    const newQuestion = document.createElement('div');
    newQuestion.className = 'question-item card mb-3';
    newQuestion.setAttribute('data-index', questionCount);
    
    newQuestion.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Question ${questionCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-question">
                    <i class="fas fa-times"></i> Remove
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label">Question</label>
                <textarea name="questions[${questionCount}][question]" class="form-control" rows="2" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Question Type</label>
                    <select name="questions[${questionCount}][type]" class="form-select question-type" required>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True/False</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Points</label>
                    <input type="number" name="questions[${questionCount}][points]" class="form-control" value="1" min="1" required>
                </div>
            </div>

            <div class="options-container">
                <label class="form-label">Options (one per line)</label>
                <textarea name="questions[${questionCount}][options]" class="form-control mb-2" rows="4" placeholder="Option A&#10;Option B&#10;Option C&#10;Option D"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Correct Answer</label>
                <input type="text" name="questions[${questionCount}][correct_answer]" class="form-control" placeholder="Enter the correct option">
            </div>

            <div class="mb-3">
                <label class="form-label">Image (Optional)</label>
                <input type="file" name="questions[${questionCount}][image]" class="form-control" accept="image/*">
            </div>
        </div>
    `;
    
    container.appendChild(newQuestion);
    questionCount++;
    updateRemoveButtons();
});

document.getElementById('questionsContainer').addEventListener('click', function(e) {
    if (e.target.closest('.remove-question')) {
        e.target.closest('.question-item').remove();
        updateRemoveButtons();
        updateQuestionNumbers();
    }
});

function updateRemoveButtons() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((q, index) => {
        const removeBtn = q.querySelector('.remove-question');
        if (questions.length > 1) {
            removeBtn.style.display = 'block';
        } else {
            removeBtn.style.display = 'none';
        }
    });
}

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((q, index) => {
        q.querySelector('h6').textContent = `Question ${index + 1}`;
    });
}

document.getElementById('quizForm').addEventListener('submit', function(e) {
    const optionsTextareas = document.querySelectorAll('.options-container textarea');
    optionsTextareas.forEach(textarea => {
        if (textarea.value.trim()) {
            const options = textarea.value.split('\n').filter(opt => opt.trim());
            const index = textarea.closest('.question-item').getAttribute('data-index');
            
            textarea.name = `questions[${index}][options][]`;
            textarea.value = '';
            
            const container = textarea.parentElement;
            options.forEach(option => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `questions[${index}][options][]`;
                input.value = option.trim();
                container.appendChild(input);
            });
        }
    });
});
</script>
@endpush
