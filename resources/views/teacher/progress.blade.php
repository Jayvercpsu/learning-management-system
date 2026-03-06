@extends('layouts.app')

@section('title', 'Student Progress - ' . $student->name)

@section('sidebar')
    @include ('teacher.sidebar')
@endsection

@push('styles')
<style>
    .progress-header {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1rem 1.1rem;
        background: #ffffff;
    }

    .student-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #1e40af;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .metric-tile {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        padding: 0.95rem;
        height: 100%;
    }

    .metric-label {
        color: #6b7280;
        font-size: 0.83rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.2rem;
    }

    .metric-value {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .summary-meter {
        height: 10px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
    }

    .summary-meter-bar {
        height: 100%;
        background: #2563eb;
        border-radius: 999px;
        transition: width 0.5s ease;
    }

    .activity-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #eef2f7;
    }

    .activity-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
</style>
@endpush

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <a href="{{ route('teacher.students') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to Students
        </a>
        <span class="text-muted small">Live data from submitted quiz attempts</span>
    </div>

    <div class="progress-header mb-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="student-avatar"><i class="fas fa-user-graduate"></i></span>
                <div>
                    <h4 class="mb-1">{{ $student->name }}</h4>
                    <p class="mb-0 text-muted">{{ $student->email }}</p>
                    <small class="text-muted">
                        ID: {{ $student->student_id ?? 'N/A' }} |
                        {{ $student->course ?? 'N/A' }} - {{ $student->section ?? 'N/A' }}
                    </small>
                </div>
            </div>
            <div class="text-end">
                <div class="text-muted small">Member Since</div>
                <div class="fw-semibold">{{ $student->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <div class="metric-tile">
                <div class="metric-label">Quizzes Taken</div>
                <p class="metric-value">{{ $progress['quizzes_taken'] }}</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="metric-tile">
                <div class="metric-label">Average Score</div>
                <p class="metric-value">{{ number_format($progress['average_score'], 1) }}%</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="metric-tile">
                <div class="metric-label">Highest Score</div>
                <p class="metric-value">{{ number_format($progress['highest_score'], 1) }}%</p>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="metric-tile">
                <div class="metric-label">Total Time</div>
                <p class="metric-value">{{ $progress['total_time'] }}</p>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-table me-2 text-primary"></i>Quiz Attempts</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if($quizResults->isNotEmpty())
                            <table class="table table-hover align-middle mb-0 js-data-table">
                                <thead>
                                    <tr>
                                        <th>Quiz</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                        <th>Points</th>
                                        <th>Duration</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quizResults as $result)
                                        @php
                                            $quizTitle = optional($result->quiz)->title ?? 'Deleted quiz';
                                            $passingScore = optional($result->quiz)->passing_score ?? 75;
                                            $hasScore = $result->score !== null;
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $quizTitle }}</td>
                                            <td>
                                                @if($hasScore)
                                                    <span class="badge bg-{{ $result->score >= $passingScore ? 'success' : 'danger' }}">
                                                        {{ number_format($result->score, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$result->quiz)
                                                    <span class="badge bg-secondary">Quiz Deleted</span>
                                                @elseif(!$result->is_checked || !$hasScore)
                                                    <span class="badge bg-warning text-dark">For Grading</span>
                                                @elseif($result->score >= $passingScore)
                                                    <span class="badge bg-success">Passed</span>
                                                @else
                                                    <span class="badge bg-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td>{{ $result->earned_points }}/{{ $result->total_points }}</td>
                                            <td>{{ $result->duration_display }}</td>
                                            <td>{{ optional($result->submitted_at)->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-5 text-muted">
                                No submitted quiz attempts yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Performance Overview</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Average Performance</span>
                        <span class="fw-semibold">{{ number_format($progress['average_score'], 1) }}%</span>
                    </div>
                    <div class="summary-meter mb-3">
                        <div class="summary-meter-bar" style="width: {{ max(0, min(100, $progress['average_score'])) }}%;"></div>
                    </div>
                    <small class="text-muted">
                        Based on {{ $progress['quizzes_taken'] }} submitted attempt{{ $progress['quizzes_taken'] === 1 ? '' : 's' }}.
                    </small>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Recent Activity</h6>
                </div>
                <div class="card-body">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <small class="text-muted d-block mb-1">{{ $activity->created_at->diffForHumans() }}</small>
                            <p class="mb-0">{{ $activity->description }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No recent activity.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
