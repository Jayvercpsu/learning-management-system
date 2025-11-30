@extends('layouts.app')

@section('title', 'My Quiz Results')

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
    <a href="{{ route('student.quizzes') }}" class="nav-link">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('student.quizzes.results') }}" class="nav-link active">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>
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
                            <td>{{ $attempt->quiz->title }}</td>
                            <td>
                                @if($attempt->is_checked && $attempt->score !== null)
                                    <span class="badge bg-{{ $attempt->score >= $attempt->quiz->passing_score ? 'success' : 'danger' }}">
                                        {{ number_format($attempt->score, 2) }}%
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($attempt->is_checked && $attempt->score !== null)
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
                                <a href="{{ route('student.quizzes.result', $attempt) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($attempt->is_checked && $attempt->score !== null)
                                    <a href="{{ route('student.quizzes.download', $attempt) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
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