@extends('layouts.app')

@section('title', 'Manage Students')

@section('sidebar')
@include ('admin.sidebar')
@endsection

@push('styles')
<style>
    .admin-table-wrap {
        overflow-x: auto;
        overflow-y: visible;
    }

    .admin-table {
        table-layout: auto;
        width: 100%;
    }

    .admin-table th,
    .admin-table td {
        word-break: break-word;
        vertical-align: middle;
    }

    .admin-table .email-cell {
        word-break: break-all;
    }

    .action-cell {
        width: 88px;
    }

    .action-menu-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-menu .dropdown-item i {
        width: 16px;
        margin-right: 0.45rem;
    }

    .action-menu {
        position: relative;
    }

    .action-menu.show {
        z-index: 1200;
    }

    .action-menu .dropdown-menu {
        z-index: 1201;
        min-width: 13rem;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h2 class="mb-0">Manage Students</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="admin-table-wrap">
            <table class="table table-hover align-middle mb-0 admin-table js-data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Registered</th>
                        <th class="text-center action-cell no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            <td class="email-cell">{{ $student->email }}</td>
                            <td>{{ $student->phone ?? 'N/A' }}</td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td class="text-center action-cell">
                                <div class="dropdown action-menu dropstart">
                                    <button class="btn btn-sm btn-outline-secondary action-menu-btn" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('admin.users.edit', $student) }}" class="dropdown-item">
                                                <i class="fas fa-edit"></i>Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item text-danger js-open-confirm"
                                                    data-action-url="{{ route('admin.students.delete', $student) }}"
                                                    data-action-method="DELETE"
                                                    data-action-title="Delete Student"
                                                    data-action-message="Delete {{ $student->name }}? This action cannot be undone."
                                                    data-action-button="Delete"
                                                    data-action-button-class="btn-danger">
                                                <i class="fas fa-trash"></i>Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="adminActionConfirmModal" tabindex="-1" aria-labelledby="adminActionConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminActionConfirmModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="adminActionConfirmMessage">Are you sure?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="adminActionConfirmForm" method="POST" class="m-0">
                    @csrf
                    <span id="adminActionConfirmMethod"></span>
                    <button type="submit" id="adminActionConfirmButton" class="btn btn-danger">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const modalElement = document.getElementById('adminActionConfirmModal');
        if (!modalElement || typeof bootstrap === 'undefined') {
            return;
        }

        const modal = new bootstrap.Modal(modalElement);
        const form = document.getElementById('adminActionConfirmForm');
        const methodHolder = document.getElementById('adminActionConfirmMethod');
        const title = document.getElementById('adminActionConfirmModalLabel');
        const message = document.getElementById('adminActionConfirmMessage');
        const submitBtn = document.getElementById('adminActionConfirmButton');

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('.js-open-confirm');
            if (!trigger) {
                return;
            }

            const actionUrl = trigger.getAttribute('data-action-url') || '#';
            const actionMethod = (trigger.getAttribute('data-action-method') || 'POST').toUpperCase();
            const actionTitle = trigger.getAttribute('data-action-title') || 'Confirm Action';
            const actionMessage = trigger.getAttribute('data-action-message') || 'Are you sure?';
            const actionButton = trigger.getAttribute('data-action-button') || 'Confirm';
            const actionButtonClass = trigger.getAttribute('data-action-button-class') || 'btn-primary';

            form.setAttribute('action', actionUrl);
            title.textContent = actionTitle;
            message.textContent = actionMessage;
            submitBtn.textContent = actionButton;
            submitBtn.className = 'btn ' + actionButtonClass;

            if (actionMethod === 'DELETE' || actionMethod === 'PUT' || actionMethod === 'PATCH') {
                methodHolder.innerHTML = '<input type="hidden" name="_method" value="' + actionMethod + '">';
            } else {
                methodHolder.innerHTML = '';
            }

            modal.show();
        });
    })();
</script>
@endpush
