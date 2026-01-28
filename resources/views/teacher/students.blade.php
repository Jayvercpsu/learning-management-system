@extends('layouts.app')

@section('title', 'Students')

@section('sidebar')
    @include ('teacher.sidebar')
@endsection

@push('styles')
<style>
    .student-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .student-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .table-hover tbody tr {
        transition: all 0.3s ease;
    }

    .table-hover tbody tr:hover {
        background: linear-gradient(to right, rgba(102, 126, 234, 0.05), transparent);
        transform: translateX(3px);
    }

    .action-btn {
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .stats-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stats-badge i {
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Registered Students</h2>
        <div class="stats-badge">
            <i class="fas fa-users"></i>
            <span>{{ $students->total() }} Students</span>
        </div>
    </div>

    <div class="card student-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Student ID</th>
                            <th>Registered</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <strong>{{ $student->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td>{{ $student->course ?? 'N/A' }}</td>
                                <td>{{ $student->section ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $student->student_id ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i>{{ $student->created_at->format('M d, Y') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('teacher.students.progress', $student->id) }}" 
                                       class="btn btn-sm btn-primary action-btn" 
                                       title="View Progress">
                                        <i class="fas fa-chart-line me-1"></i>View Progress
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No students registered yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }
</style>
@endpush