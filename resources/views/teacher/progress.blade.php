@extends('layouts.app')

@section('title', 'Student Progress - ' . $student->name)

@section('sidebar')
    @include ('teacher.sidebar')
@endsection

@push('styles')
<style>
    .progress-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .progress-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .student-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border: 3px solid white;
    }

    .stat-box {
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .progress-bar-animated {
        animation: progress-animation 1s ease-out;
    }

    @keyframes progress-animation {
        from { width: 0; }
    }

    .quiz-item {
        transition: all 0.3s ease;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        border: 1px solid #e0e0e0;
    }

    .quiz-item:hover {
        background: linear-gradient(to right, rgba(102, 126, 234, 0.05), transparent);
        transform: translateX(5px);
        border-color: #667eea;
    }

    .score-badge {
        font-size: 1.2rem;
        font-weight: bold;
        padding: 8px 16px;
        border-radius: 20px;
    }

    .score-excellent {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .score-good {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .score-average {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .score-poor {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
    }

    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }

    .activity-item {
        position: relative;
        margin-bottom: 20px;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #667eea;
    }

    .circular-progress {
        position: relative;
        width: 120px;
        height: 120px;
    }

    .circular-progress svg {
        transform: rotate(-90deg);
    }

    .circular-progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.5rem;
        font-weight: bold;
        color: #667eea;
    }
</style>
@endpush

@section('content')
    <div class="mb-3">
        <a href="{{ route('teacher.students') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Students
        </a>
    </div>

    <div class="student-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="avatar-large me-3">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h2 class="mb-1">{{ $student->name }}</h2>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-envelope me-2"></i>{{ $student->email }}
                        </p>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-id-card me-2"></i>{{ $student->student_id ?? 'N/A' }} | 
                            <i class="fas fa-book ms-2 me-2"></i>{{ $student->course ?? 'N/A' }} - {{ $student->section ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box text-center">
                    <h5 class="mb-1">Member Since</h5>
                    <h3 class="mb-0">{{ $student->created_at->format('M d, Y') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card progress-card">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-check fa-2x text-primary mb-2"></i>
                    <h6 class="text-muted mb-1">Quizzes Taken</h6>
                    <h2 class="mb-0">{{ $progress['quizzes_taken'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card progress-card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                    <h6 class="text-muted mb-1">Average Score</h6>
                    <h2 class="mb-0">{{ number_format($progress['average_score'], 1) }}%</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card progress-card">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                    <h6 class="text-muted mb-1">Highest Score</h6>
                    <h2 class="mb-0">{{ $progress['highest_score'] }}%</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card progress-card">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-info mb-2"></i>
                    <h6 class="text-muted mb-1">Total Time Spent</h6>
                    <h2 class="mb-0">{{ $progress['total_time'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card progress-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-primary me-2"></i>Quiz Results</h5>
                </div>
                <div class="card-body">
                    @forelse($quizResults as $result)
                        <div class="quiz-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $result->quiz->title }}</h6>
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i>{{ $result->created_at->format('M d, Y') }} at {{ $result->created_at->format('h:i A') }}
                                    </small>
                                </div>
                                <span class="score-badge 
                                    @if($result->score >= 90) score-excellent
                                    @elseif($result->score >= 75) score-good
                                    @elseif($result->score >= 60) score-average
                                    @else score-poor
                                    @endif">
                                    {{ $result->score }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar progress-bar-animated 
                                    @if($result->score >= 90) bg-success
                                    @elseif($result->score >= 75) bg-info
                                    @elseif($result->score >= 60) bg-warning
                                    @else bg-danger
                                    @endif" 
                                    role="progressbar" 
                                    style="width: {{ $result->score }}%" 
                                    aria-valuenow="{{ $result->score }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-check-circle me-1"></i>{{ $result->correct_answers }}/{{ $result->total_questions }} correct
                                    <span class="ms-3"><i class="fas fa-clock me-1"></i>{{ $result->time_spent ?? 'N/A' }}</span>
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No quiz attempts yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card progress-card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-percentage text-success me-2"></i>Overall Progress</h5>
                </div>
                <div class="card-body text-center">
                    <div class="circular-progress mx-auto mb-3">
                        <svg width="120" height="120">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#e0e0e0" stroke-width="8"/>
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#667eea" stroke-width="8"
                                stroke-dasharray="{{ 2 * 3.14159 * 54 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 54 * (1 - $progress['average_score'] / 100) }}"
                                stroke-linecap="round"/>
                        </svg>
                        <div class="circular-progress-text">{{ number_format($progress['average_score'], 0) }}%</div>
                    </div>
                    <p class="text-muted mb-0">Average Performance</p>
                </div>
            </div>

            <div class="card progress-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-history text-info me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <small class="text-muted d-block">{{ $activity->created_at->diffForHumans() }}</small>
                                <p class="mb-0">{{ $activity->description }}</p>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No recent activity</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection