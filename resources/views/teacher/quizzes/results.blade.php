@extends('layouts.app')

@section('title', 'Quiz Results')

@section('sidebar')
@include ('teacher.sidebar')
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $quiz->title }}</h2>
        <p class="text-muted mb-0">Quiz Results and Attempts</p>
    </div>
    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>{{ $attempt->user->name }}</td>
                            <td>
                                @if($attempt->is_checked && $attempt->score !== null)
                                    <span class="badge bg-{{ $attempt->score >= $quiz->passing_score ? 'success' : 'danger' }}">
                                        {{ number_format($attempt->score, 2) }}%
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Not graded</span>
                                @endif
                            </td>
                            <td>
                                @if($attempt->is_checked)
                                    <span class="badge bg-success">Graded</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($attempt->submitted_at)
                                    {{ $attempt->submitted_at->format('M d, Y H:i') }}
                                @else
                                    <span class="text-muted">Not submitted</span>
                                @endif
                            </td>
                            <td>
                                @if($attempt->submitted_at)
                                    <a href="{{ route('teacher.quizzes.attempts.view', [$quiz, $attempt]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                @else
                                    <span class="text-muted">In Progress</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No submitted attempts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $attempts->links() }}
        </div>
    </div>
</div>
@endsection