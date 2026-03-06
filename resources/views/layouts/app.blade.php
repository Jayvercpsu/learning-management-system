<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS - Learning Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --app-bg: #f3f5f9;
            --sidebar-bg: #ffffff;
            --surface: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --sidebar-width: 260px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--app-bg);
            color: var(--text-main);
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            z-index: 1040;
            overflow-y: auto;
            transition: transform 0.28s ease;
        }

        .sidebar-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 1rem 0.9rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #111827;
        }

        .sidebar-brand i {
            color: var(--accent);
        }

        .sidebar-user {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar .nav {
            padding: 0.65rem 0.65rem 1rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: #374151;
            border-radius: 10px;
            padding: 0.62rem 0.78rem;
            margin-bottom: 0.35rem;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
            color: #6b7280;
        }

        .sidebar .nav-link:hover {
            background: #eef2ff;
            color: #111827;
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: #e0e7ff;
            color: #1e40af;
            font-weight: 600;
        }

        .sidebar .nav-link.active i {
            color: #1e40af;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.28s ease;
        }

        .top-nav {
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1020;
            backdrop-filter: blur(8px);
        }

        .content-wrap {
            padding: 1.2rem;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.08);
        }

        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn.is-loading,
        .dropdown-item.is-loading {
            opacity: 0.88;
        }

        .table > :not(caption) > * > * {
            border-color: #edf0f3;
        }

        .table > thead th {
            font-size: 0.88rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #4b5563;
            border-bottom-width: 1px;
            white-space: nowrap;
        }

        .table-hover tbody tr {
            transition: none;
        }

        .table-hover tbody tr:hover {
            transform: none;
        }

        .table-hover > tbody > tr:hover > * {
            --bs-table-accent-bg: transparent !important;
            background-color: transparent !important;
            color: inherit !important;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 9px;
            margin: 0 3px;
            border-color: #d1d5db;
            color: #374151;
            min-width: 36px;
            text-align: center;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
        }

        .dataTables_wrapper {
            padding: 0.6rem 0.75rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #fff;
        }

        .dataTables_wrapper .dataTables_filter input {
            min-width: 220px;
        }

        .dataTables_wrapper .dataTables_length select {
            min-width: 74px;
        }

        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_info {
            color: #4b5563;
            font-size: 0.92rem;
        }

        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label {
            margin-bottom: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-weight: 500;
        }

        .dataTables_wrapper .dataTables_info {
            font-weight: 500;
        }

        .dataTables_wrapper table.dataTable thead th {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .dataTables_wrapper table.dataTable.no-footer {
            border-bottom: 1px solid #e5e7eb !important;
        }

        .dataTables_wrapper .dataTables_paginate .pagination {
            justify-content: flex-end;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 1030;
            background: rgba(17, 24, 39, 0.46);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.24s ease, visibility 0.24s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .page-animate {
            animation: pageEnter 0.35s ease-out;
        }

        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body.sidebar-desktop-collapsed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-desktop-collapsed .main-content {
            margin-left: 0;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content,
            body.sidebar-desktop-collapsed .main-content {
                margin-left: 0;
            }

            .content-wrap {
                padding: 1rem;
            }
        }

        .sidebar::-webkit-scrollbar {
            width: 7px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-head">
                <h4 class="sidebar-brand"><i class="fas fa-graduation-cap me-1"></i> LMS</h4>
                <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarClose" type="button" aria-label="Close sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-user">
                <small class="text-uppercase text-muted fw-semibold d-block">{{ auth()->user()->role }}</small>
                <span class="fw-semibold">{{ auth()->user()->name }}</span>
            </div>
            @yield('sidebar')
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="main-content" id="mainContent">
            <nav class="navbar top-nav">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <button class="btn btn-outline-secondary btn-sm" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                        <i class="fas fa-outdent"></i>
                    </button>
                    <div class="dropdown ms-auto">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-edit me-2"></i>Edit Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content-wrap page-animate">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const desktopQuery = window.matchMedia('(min-width: 992px)');
            const storageKey = 'lms_sidebar_collapsed';

            if (!sidebar || !sidebarToggle) {
                return;
            }

            function setToggleIcon(collapsed) {
                sidebarToggle.innerHTML = collapsed
                    ? '<i class="fas fa-indent"></i>'
                    : '<i class="fas fa-outdent"></i>';
            }

            function setDesktopCollapsed(collapsed) {
                document.body.classList.toggle('sidebar-desktop-collapsed', collapsed);
                setToggleIcon(collapsed);
            }

            function openMobileSidebar() {
                sidebar.classList.add('mobile-open');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.add('active');
                }
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileSidebar() {
                sidebar.classList.remove('mobile-open');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
                document.body.classList.remove('overflow-hidden');
            }

            function applyInitialSidebarState() {
                if (desktopQuery.matches) {
                    const collapsed = localStorage.getItem(storageKey) === '1';
                    setDesktopCollapsed(collapsed);
                } else {
                    setDesktopCollapsed(false);
                    closeMobileSidebar();
                }
            }

            sidebarToggle.addEventListener('click', function () {
                if (desktopQuery.matches) {
                    const collapsed = !document.body.classList.contains('sidebar-desktop-collapsed');
                    setDesktopCollapsed(collapsed);
                    localStorage.setItem(storageKey, collapsed ? '1' : '0');
                    return;
                }

                if (sidebar.classList.contains('mobile-open')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            });

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeMobileSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeMobileSidebar);
            }

            window.addEventListener('resize', applyInitialSidebarState);
            applyInitialSidebarState();
        })();

        (function () {
            if (typeof window.jQuery === 'undefined' || typeof jQuery.fn.DataTable === 'undefined') {
                return;
            }

            jQuery(function ($) {
                $('.js-data-table').each(function () {
                    if ($.fn.dataTable.isDataTable(this)) {
                        return;
                    }

                    $(this).DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                        autoWidth: false,
                        columnDefs: [
                            {
                                targets: 'no-sort',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        language: {
                            search: 'Search:',
                            lengthMenu: '_MENU_',
                            info: 'On page _PAGE_ of _PAGES_, showing rows _START_ to _END_ of _TOTAL_',
                            infoEmpty: 'No rows to show',
                            paginate: {
                                previous: 'Previous',
                                next: 'Next'
                            }
                        },
                        dom: "<'row align-items-center gy-2 mb-2'<'col-sm-6'l><'col-sm-6 d-flex justify-content-sm-end'f>>" +
                            "t" +
                            "<'row align-items-center gy-2 mt-2'<'col-sm-7'i><'col-sm-5 d-flex justify-content-sm-end'p>>"
                    });
                });
            });
        })();

        (function () {
            function inferLoadingText(trigger) {
                const customText = (trigger.getAttribute('data-loading-text') || '').trim();
                if (customText) {
                    return customText;
                }

                const label = (trigger.textContent || trigger.value || '').trim().toLowerCase();

                if (label.includes('upload')) return 'Uploading...';
                if (label.includes('save')) return 'Saving...';
                if (label.includes('submit')) return 'Submitting...';
                if (label.includes('delete') || label.includes('remove')) return 'Deleting...';
                if (label.includes('update')) return 'Updating...';
                if (label.includes('approve')) return 'Approving...';
                if (label.includes('reject')) return 'Rejecting...';
                if (label.includes('login') || label.includes('sign in')) return 'Signing in...';
                if (label.includes('logout') || label.includes('sign out')) return 'Signing out...';
                if (label.includes('register') || label.includes('sign up')) return 'Registering...';

                return 'Processing...';
            }

            function setButtonLoadingState(button, loadingText) {
                if (!button || button.dataset.loadingApplied === '1') {
                    return;
                }

                button.dataset.loadingApplied = '1';
                button.disabled = true;
                button.classList.add('is-loading');
                button.setAttribute('aria-busy', 'true');

                if (button.tagName === 'INPUT') {
                    if (!button.dataset.originalValue) {
                        button.dataset.originalValue = button.value;
                    }
                    button.value = loadingText;
                    return;
                }

                if (!button.dataset.originalHtml) {
                    button.dataset.originalHtml = button.innerHTML;
                }

                button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
            }

            function getSubmitButtons(form) {
                return Array.from(form.querySelectorAll('button[type="submit"], input[type="submit"]'));
            }

            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (form.hasAttribute('data-no-loading')) {
                    return;
                }

                const method = (form.getAttribute('method') || 'GET').toUpperCase();
                if (method === 'GET') {
                    return;
                }

                if (form.dataset.submitting === '1') {
                    event.preventDefault();
                    return;
                }

                let submitter = event.submitter;
                if (!submitter && form.contains(document.activeElement)) {
                    const active = document.activeElement;
                    if (active && (active.tagName === 'BUTTON' || active.tagName === 'INPUT')) {
                        const type = (active.getAttribute('type') || 'submit').toLowerCase();
                        if (type === 'submit') {
                            submitter = active;
                        }
                    }
                }

                const submitButtons = getSubmitButtons(form);
                if (!submitter && submitButtons.length > 0) {
                    submitter = submitButtons[0];
                }

                form.dataset.submitting = '1';

                if (submitter) {
                    setButtonLoadingState(submitter, inferLoadingText(submitter));
                }

                submitButtons.forEach(function (button) {
                    if (button !== submitter) {
                        button.disabled = true;
                    }
                });
            }, true);

            document.addEventListener('click', function (event) {
                const trigger = event.target.closest('[data-submit-form]');
                if (!trigger) {
                    return;
                }

                const formId = trigger.getAttribute('data-submit-form');
                if (!formId) {
                    return;
                }

                const form = document.getElementById(formId);
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                event.preventDefault();

                if (trigger.tagName === 'BUTTON' || trigger.tagName === 'INPUT') {
                    setButtonLoadingState(trigger, inferLoadingText(trigger));
                }

                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
