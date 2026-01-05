<!-- Delete Modal for each quiz -->
<div class="modal fade" id="deleteModal{{ $quiz->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $quiz->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel{{ $quiz->id }}">
                    <i class="fas fa-trash-alt"></i> Delete Quiz?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h5 class="mb-3">Are you sure you want to delete this quiz?</h5>
                <p class="text-muted mb-2"><strong>{{ $quiz->title }}</strong></p>
                <p class="text-muted">This action cannot be undone. All quiz data, questions, and student attempts will
                    be permanently deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <form action="{{ route('teacher.quizzes.destroy', $quiz) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Quiz
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

