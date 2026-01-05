@extends('layouts.app')

@section('title', 'My Quiz Results')

@section('sidebar')
@include ('student.sidebar')
@endsection

@section('content')
<h2 class="mb-4">My Quiz Results</h2>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Quiz</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>
                                @if($attempt->quiz)
                                    {{ $attempt->quiz->title }}
                                @else
                                    <span class="text-muted"><i>(Quiz Deleted)</i></span>
                                @endif
                            </td>
                            <td>
                                @if($attempt->quiz && $attempt->is_checked && $attempt->score !== null)
                                    <span class="badge bg-{{ $attempt->score >= $attempt->quiz->passing_score ? 'success' : 'danger' }}">
                                        {{ number_format($attempt->score, 2) }}%
                                    </span>
                                @elseif(!$attempt->quiz)
                                    <span class="badge bg-secondary">N/A</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if(!$attempt->quiz)
                                    <span class="badge bg-secondary">Quiz Deleted</span>
                                @elseif($attempt->is_checked && $attempt->score !== null)
                                    @if($attempt->score >= $attempt->quiz->passing_score)
                                        <span class="badge bg-success">Passed</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                @else
                                    <span class="badge bg-warning">Grading</span>
                                @endif
                            </td>
                            <td>{{ $attempt->submitted_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($attempt->quiz)
                                    <a href="{{ route('student.quizzes.result', $attempt) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($attempt->is_checked && $attempt->score !== null)
                                        <a href="{{ route('student.quizzes.download', $attempt) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i> PDF
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted small">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No quiz attempts yet.</td>
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