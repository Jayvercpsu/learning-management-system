@extends('layouts.app')

@section('title', 'Manage Teachers')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('admin.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('admin.teachers') }}" class="nav-link active">
        <i class="fas fa-chalkboard-teacher"></i> Teachers
    </a>
    <a href="{{ route('admin.students') }}" class="nav-link">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Teachers</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->phone ?? 'N/A' }}</td>
                            <td>
                                @if($teacher->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                            <td>
                                @if(!$teacher->is_approved)
                                    <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('admin.users.edit', $teacher) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.teachers.delete', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No teachers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection
