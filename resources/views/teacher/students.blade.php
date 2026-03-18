@extends('layouts.app')

@section('title', 'Students')

@section('sidebar')
    @include ('teacher.sidebar')
@endsection

@push('styles')
<style>
    .students-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .student-count {
        border: 1px solid #dbe3ef;
        background: #ffffff;
        border-radius: 999px;
        padding: 0.38rem 0.8rem;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 600;
    }

    .student-name {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #1f2937;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
    }

    .action-btn {
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
    <div class="students-header flex-wrap">
        <h2 class="mb-0">Registered Students</h2>
        <span class="student-count"><i class="fas fa-users me-1"></i>{{ $students->count() }} Students</span>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($students->isEmpty())
                <div class="alert alert-info m-3 mb-0">No students registered yet.</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 js-data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Student ID</th>
                            <th>Registered</th>
                            <th class="text-center no-sort">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    <div class="student-name">
                                        <span class="avatar-circle"><i class="fas fa-user-graduate"></i></span>
                                        <span class="fw-semibold">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td>{{ $student->course ?? 'N/A' }}</td>
                                <td>{{ $student->section ?? 'N/A' }}</td>
                                <td>{{ $student->student_id ?? 'N/A' }}</td>
                                <td>{{ $student->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('teacher.students.progress', $student->id) }}" class="btn btn-sm btn-primary action-btn">
                                        <i class="fas fa-chart-line me-1"></i>View Progress
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
