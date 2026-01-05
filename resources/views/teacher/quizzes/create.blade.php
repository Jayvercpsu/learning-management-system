@extends('layouts.app')

@section('title', 'Create Quiz')

@section('sidebar')
@include ('teacher.sidebar')
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
                            <textarea name="questions[0][question]" class="form-control question-text" rows="2" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Question Type</label>
                            <select name="questions[0][type]" class="form-select question-type" data-index="0" required>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="true_false">True/False</option>
                                <option value="essay">Problem Solving</option>
                            </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Points</label>
                                <input type="number" name="questions[0][points]" class="form-control" value="1" min="1" required>
                            </div>
                        </div>

                        <div class="options-container" data-index="0">
                            <label class="form-label">Options</label>
                            <div class="options-list">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">A.</span>
                                    <input type="text" class="form-control option-input" placeholder="Enter option A" data-letter="A">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">B.</span>
                                    <input type="text" class="form-control option-input" placeholder="Enter option B" data-letter="B">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">C.</span>
                                    <input type="text" class="form-control option-input" placeholder="Enter option C" data-letter="C">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">D.</span>
                                    <input type="text" class="form-control option-input" placeholder="Enter option D" data-letter="D">
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-option-btn">
                                <i class="fas fa-plus"></i> Add More Options
                            </button>
                        </div>

                        <div class="mb-3 correct-answer-container">
                            <label class="form-label">Correct Answer</label>
                            <select name="questions[0][correct_answer]" class="form-select correct-answer-select">
                                <option value="">Select correct answer</option>
                            </select>
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
const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

function updateCorrectAnswerDropdown(index) {
    const optionsContainer = document.querySelector(`.options-container[data-index="${index}"]`);
    const correctAnswerSelect = optionsContainer.parentElement.querySelector('.correct-answer-select');
    const optionInputs = optionsContainer.querySelectorAll('.option-input');
    
    correctAnswerSelect.innerHTML = '<option value="">Select correct answer</option>';
    
    optionInputs.forEach((input, idx) => {
        if (input.value.trim()) {
            const letter = input.getAttribute('data-letter');
            const option = document.createElement('option');
            option.value = input.value.trim();
            option.textContent = `${letter}. ${input.value.trim()}`;
            correctAnswerSelect.appendChild(option);
        }
    });
}

function toggleQuestionFields(questionItem, type) {
    const optionsContainer = questionItem.querySelector('.options-container');
    const correctAnswerContainer = questionItem.querySelector('.correct-answer-container');
    const correctAnswerSelect = questionItem.querySelector('.correct-answer-select');
    
    if (type === 'multiple_choice') {
optionsContainer.style.display = 'block';
        correctAnswerContainer.style.display = 'block';
correctAnswerSelect.required = true;
        
const optionInputs = optionsContainer.querySelectorAll('.option-input');
        optionInputs.forEach(input => {
            input.required = true;
        });
} else if (type === 'true_false') {
        optionsContainer.style.display = 'none';
        correctAnswerContainer.style.display = 'block';
correctAnswerSelect.required = true;
        
        correctAnswerSelect.innerHTML = `
            <option value="">Select correct answer</option>
            <option value="True">True</option>
            <option value="False">False</option>
        `;
} else if (type === 'essay') {
    optionsContainer.style.display = 'none';
    correctAnswerContainer.style.display = 'none';
    correctAnswerSelect.required = false;
    const optionInputs = optionsContainer.querySelectorAll('.option-input');
    optionInputs.forEach(input => {
        input.required = false;
    });
}
}

document.getElementById('questionsContainer').addEventListener('change', function(e) {
    if (e.target.classList.contains('question-type')) {
        const questionItem = e.target.closest('.question-item');
        const type = e.target.value;
        toggleQuestionFields(questionItem, type);
    }
    
    if (e.target.classList.contains('option-input')) {
        const index = e.target.closest('.options-container').getAttribute('data-index');
        updateCorrectAnswerDropdown(index);
    }
});

document.getElementById('questionsContainer').addEventListener('input', function(e) {
    if (e.target.classList.contains('option-input')) {
        const index = e.target.closest('.options-container').getAttribute('data-index');
        updateCorrectAnswerDropdown(index);
    }
});

document.getElementById('questionsContainer').addEventListener('click', function(e) {
    if (e.target.closest('.add-option-btn')) {
        const optionsContainer = e.target.closest('.options-container');
        const optionsList = optionsContainer.querySelector('.options-list');
        const currentOptions = optionsList.querySelectorAll('.input-group').length;
        
        if (currentOptions < 26) {
            const letter = letters[currentOptions];
            const newOption = document.createElement('div');
            newOption.className = 'input-group mb-2';
            newOption.innerHTML = `
                <span class="input-group-text">${letter}.</span>
                <input type="text" class="form-control option-input" placeholder="Enter option ${letter}" data-letter="${letter}">
                <button type="button" class="btn btn-outline-danger remove-option-btn">
                    <i class="fas fa-times"></i>
                </button>
            `;
            optionsList.appendChild(newOption);
            
            const index = optionsContainer.getAttribute('data-index');
            updateCorrectAnswerDropdown(index);
        }
    }
    
    if (e.target.closest('.remove-option-btn')) {
        const optionsContainer = e.target.closest('.options-container');
        const optionsList = optionsContainer.querySelector('.options-list');
        const currentOptions = optionsList.querySelectorAll('.input-group').length;
        
        if (currentOptions > 2) {
            e.target.closest('.input-group').remove();
            
            const remainingOptions = optionsList.querySelectorAll('.input-group');
            remainingOptions.forEach((option, idx) => {
                const letter = letters[idx];
                option.querySelector('.input-group-text').textContent = `${letter}.`;
                const input = option.querySelector('.option-input');
                input.setAttribute('data-letter', letter);
                input.setAttribute('placeholder', `Enter option ${letter}`);
            });
            
            const index = optionsContainer.getAttribute('data-index');
            updateCorrectAnswerDropdown(index);
        }
    }
});

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
                <textarea name="questions[${questionCount}][question]" class="form-control question-text" rows="2" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Question Type</label>
                <select name="questions[${questionCount}][type]" class="form-select question-type" data-index="${questionCount}" required>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="essay">Problem Solving</option>
                </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Points</label>
                    <input type="number" name="questions[${questionCount}][points]" class="form-control" value="1" min="1" required>
                </div>
            </div>

            <div class="options-container" data-index="${questionCount}">
                <label class="form-label">Options</label>
                <div class="options-list">
                    <div class="input-group mb-2">
                        <span class="input-group-text">A.</span>
                        <input type="text" class="form-control option-input" placeholder="Enter option A" data-letter="A">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">B.</span>
                        <input type="text" class="form-control option-input" placeholder="Enter option B" data-letter="B">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">C.</span>
                        <input type="text" class="form-control option-input" placeholder="Enter option C" data-letter="C">
                    </div>
                    <div class="input-group mb-2">
                        <span class="input-group-text">D.</span>
                        <input type="text" class="form-control option-input" placeholder="Enter option D" data-letter="D">
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary add-option-btn">
                    <i class="fas fa-plus"></i> Add More Options
                </button>
            </div>

            <div class="mb-3 correct-answer-container">
                <label class="form-label">Correct Answer</label>
                <select name="questions[${questionCount}][correct_answer]" class="form-select correct-answer-select">
                    <option value="">Select correct answer</option>
                </select>
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
    const questionItems = document.querySelectorAll('.question-item');
    
    questionItems.forEach((questionItem, qIndex) => {
        const index = questionItem.getAttribute('data-index');
        const type = questionItem.querySelector('.question-type').value;
        const optionsContainer = questionItem.querySelector('.options-container');
        
        if (type === 'multiple_choice') {
            const optionInputs = optionsContainer.querySelectorAll('.option-input');
            const optionsArray = [];
            
            optionInputs.forEach(input => {
                if (input.value.trim()) {
                    optionsArray.push(input.value.trim());
                }
            });
            
            optionsArray.forEach(option => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `questions[${index}][options][]`;
                hiddenInput.value = option;
                optionsContainer.appendChild(hiddenInput);
            });
        } else if (type === 'true_false') {
            const hiddenInput1 = document.createElement('input');
            hiddenInput1.type = 'hidden';
            hiddenInput1.name = `questions[${index}][options][]`;
            hiddenInput1.value = 'True';
            optionsContainer.appendChild(hiddenInput1);
            
            const hiddenInput2 = document.createElement('input');
            hiddenInput2.type = 'hidden';
            hiddenInput2.name = `questions[${index}][options][]`;
            hiddenInput2.value = 'False';
            optionsContainer.appendChild(hiddenInput2);
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const firstQuestion = document.querySelector('.question-item');
    if (firstQuestion) {
        const type = firstQuestion.querySelector('.question-type').value;
        toggleQuestionFields(firstQuestion, type);
    }
});
</script>
@endpush