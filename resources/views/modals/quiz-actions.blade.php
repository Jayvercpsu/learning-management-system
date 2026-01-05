<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Leave Quiz?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                <h5 class="mb-3">Are you sure you want to leave?</h5>
                <p class="text-muted">Your progress will be lost and you'll need to start over.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Stay
                </button>
                <a href="{{ route('student.quizzes') }}" class="btn btn-warning">
                    <i class="fas fa-sign-out-alt"></i> Leave Quiz
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="submitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="submitModalLabel">
                    <i class="fas fa-check-circle"></i> Submit Quiz?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-paper-plane fa-3x text-success mb-3"></i>
                <h5 class="mb-3">Ready to submit your quiz?</h5>
                <p class="text-muted">You cannot change your answers after submission. Make sure you've reviewed all questions.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-edit"></i> Review Answers
                </button>
                <button type="submit" class="btn btn-success" onclick="document.getElementById('quizForm').submit();">
                    <i class="fas fa-check"></i> Submit Now
                </button>
            </div>
        </div>
    </div>
</div>