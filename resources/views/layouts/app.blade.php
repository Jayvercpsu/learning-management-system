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
            --app-bg-accent: #e9efff;
            --sidebar-bg: #ffffff;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --nav-hover: #eef2ff;
            --nav-active: #e0e7ff;
            --nav-active-text: #1e40af;
            --shadow-soft: 0 6px 22px rgba(15, 23, 42, 0.05);
            --shadow-hover: 0 12px 26px rgba(15, 23, 42, 0.08);
            --sidebar-width: 260px;
        }

        body.dark-mode {
            --app-bg: #020617;
            --app-bg-accent: #0f172a;
            --sidebar-bg: #0b1220;
            --surface: #0f172a;
            --surface-soft: #111827;
            --text-main: #e5e7eb;
            --text-muted: #94a3b8;
            --border-color: #1e293b;
            --accent: #60a5fa;
            --accent-hover: #3b82f6;
            --nav-hover: #172032;
            --nav-active: #1e3a8a3d;
            --nav-active-text: #bfdbfe;
            --shadow-soft: 0 8px 24px rgba(2, 6, 23, 0.35);
            --shadow-hover: 0 12px 28px rgba(2, 6, 23, 0.45);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(160deg, var(--app-bg) 0%, var(--app-bg-accent) 100%);
            color: var(--text-main);
            transition: background-color 0.25s ease, color 0.25s ease;
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
            display: flex;
            flex-direction: column;
            overflow: hidden;
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
            color: var(--text-main);
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

        .sidebar-nav {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
        }

        .sidebar-footer {
            padding: 0.85rem;
            border-top: 1px solid var(--border-color);
            background: var(--surface);
        }

        .sidebar-logout-btn {
            width: 100%;
            border-radius: 10px;
            font-weight: 600;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: var(--text-main);
            border-radius: 10px;
            padding: 0.62rem 0.78rem;
            margin-bottom: 0.35rem;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
            color: var(--text-muted);
        }

        .sidebar .nav-link:hover {
            background: var(--nav-hover);
            color: var(--text-main);
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: var(--nav-active);
            color: var(--nav-active-text);
            font-weight: 600;
        }

        .sidebar .nav-link.active i {
            color: var(--nav-active-text);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.28s ease;
        }

        .top-nav {
            background: var(--surface);
            background: color-mix(in srgb, var(--surface) 92%, transparent);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1020;
            backdrop-filter: blur(8px);
        }

        .content-wrap {
            padding: 1.25rem;
            max-width: 1500px;
            margin: 0 auto;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: var(--shadow-soft);
            background: var(--surface);
            color: var(--text-main);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
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
            border-color: var(--border-color);
        }

        .table > thead th {
            font-size: 0.88rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: var(--text-muted);
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
            border-color: var(--border-color);
            color: var(--text-main);
            min-width: 36px;
            text-align: center;
            background: var(--surface);
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
            border: 1px solid var(--border-color);
            background: var(--surface);
            color: var(--text-main);
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
            color: var(--text-muted);
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
            background: var(--surface-soft);
            border-bottom: 1px solid var(--border-color) !important;
        }

        .dataTables_wrapper table.dataTable.no-footer {
            border-bottom: 1px solid var(--border-color) !important;
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

        .nav-toolbar {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .theme-toggle {
            min-width: 94px;
        }

        .notification-menu {
            width: min(92vw, 390px);
            max-width: min(92vw, 390px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            background: var(--surface);
        }

        .notification-header {
            border-bottom: 1px solid var(--border-color);
            padding: 0.7rem 0.85rem;
            background: var(--surface-soft);
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .notification-list {
            max-height: min(62vh, 420px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .notification-item {
            padding: 0.75rem 0.85rem;
            border-bottom: 1px solid var(--border-color);
        }

        .notification-item:last-child {
            border-bottom: 0;
        }

        .notification-item.unread {
            background: var(--surface-soft);
            background: color-mix(in srgb, var(--accent) 11%, var(--surface));
        }

        .notification-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .notification-body {
            color: var(--text-muted);
            font-size: 0.84rem;
            margin-bottom: 0.55rem;
            line-height: 1.35;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .notification-time {
            color: var(--text-muted);
            font-size: 0.76rem;
        }

        .notification-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.4rem;
            margin-top: 0.55rem;
        }

        .notification-actions form {
            margin: 0;
        }

        .notification-actions .btn {
            width: 100%;
            white-space: nowrap;
        }

        .notification-modal .modal-content {
            border: 1px solid var(--border-color);
            background: var(--surface);
            color: var(--text-main);
        }

        .notification-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
            background: var(--surface-soft);
        }

        .notification-modal .modal-footer {
            border-top: 1px solid var(--border-color);
            background: var(--surface);
        }

        .notification-modal .modal-body {
            padding: 0;
            background: var(--surface);
        }

        .notification-modal .notification-list {
            max-height: min(68vh, 540px);
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-main);
        }

        .btn-outline-secondary:hover {
            background: var(--nav-hover);
            color: var(--text-main);
            border-color: var(--accent);
        }

        .dropdown-menu {
            border-color: var(--border-color);
            background: var(--surface);
        }

        .dropdown-item {
            color: var(--text-main);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: var(--nav-hover);
            color: var(--text-main);
        }

        .dropdown-divider {
            border-top-color: var(--border-color);
        }

        .form-control,
        .form-select {
            background: var(--surface);
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.2);
            box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--accent) 22%, transparent);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .card-header,
        .card-footer,
        .list-group-item {
            background: var(--surface);
            border-color: var(--border-color);
            color: var(--text-main);
        }

        .bg-white {
            background-color: var(--surface) !important;
            color: var(--text-main) !important;
        }

        .bg-light {
            background-color: var(--surface-soft) !important;
            color: var(--text-main) !important;
        }

        .border,
        .border-top,
        .border-end,
        .border-bottom,
        .border-start {
            border-color: var(--border-color) !important;
        }

        .alert-success {
            border-color: #9ad9b0;
            border-color: color-mix(in srgb, #16a34a 30%, var(--border-color));
        }

        .alert-danger {
            border-color: #ef9a9a;
            border-color: color-mix(in srgb, #dc2626 30%, var(--border-color));
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

            .theme-toggle {
                min-width: auto;
            }
        }

        @media (max-width: 991.98px) {
            .nav-toolbar {
                gap: 0.4rem;
            }

            .notification-menu {
                width: calc(100vw - 1rem);
                max-width: calc(100vw - 1rem);
                right: 0.5rem !important;
                left: auto !important;
                transform: none !important;
                border-radius: 10px;
            }

            .notification-list {
                max-height: min(65vh, 520px);
            }

            .notification-actions {
                grid-template-columns: 1fr;
            }

            .notification-actions .btn {
                white-space: normal;
            }

            .notification-dropdown-wrapper .notification-menu {
                display: none !important;
            }
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 7px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
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
            <div class="sidebar-nav">
                @yield('sidebar')
            </div>
            <div class="sidebar-footer d-lg-none">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger sidebar-logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="main-content" id="mainContent">
            @php
                $notificationsEnabled = \Illuminate\Support\Facades\Schema::hasTable('notifications');
                $unreadNotificationsCount = $notificationsEnabled ? auth()->user()->unreadNotifications()->count() : 0;
                $latestNotifications = $notificationsEnabled
                    ? auth()->user()->notifications()->latest()->take(8)->get()
                    : collect();
            @endphp
            <nav class="navbar top-nav">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <button class="btn btn-outline-secondary btn-sm" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                        <i class="fas fa-outdent"></i>
                    </button>

                    <div class="nav-toolbar ms-auto">
                        <button class="btn btn-outline-secondary btn-sm theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode">
                            <i class="fas fa-moon me-1"></i><span class="d-none d-sm-inline">Dark</span>
                        </button>

                        <div class="dropdown notification-dropdown-wrapper">
                            <button class="btn btn-outline-secondary btn-sm position-relative" id="notificationDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @if($unreadNotificationsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
                                <div class="notification-header d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Notifications</span>
                                    @if($unreadNotificationsCount > 0)
                                        <form action="{{ route('notifications.readAll') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none">Mark all as read</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="notification-list">
                                    @forelse($latestNotifications as $notification)
                                        @php
                                            $data = $notification->data;
                                            $title = $data['title'] ?? 'Notification';
                                            $message = $data['message'] ?? '';
                                            $openLabel = $data['action_label'] ?? 'Open';
                                        @endphp
                                        <div class="notification-item {{ $notification->read_at === null ? 'unread' : '' }}">
                                            <div class="notification-title">{{ $title }}</div>
                                            <div class="notification-body">{{ $message }}</div>
                                            <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                            <div class="notification-actions">
                                                <a href="{{ route('notifications.open', $notification->id) }}" class="btn btn-primary btn-sm">{{ $openLabel }}</a>
                                                @if($notification->read_at === null)
                                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm">Mark as Read</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="notification-item">
                                            <p class="text-muted mb-0 small">No notifications yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="dropdown">
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
                </div>
            </nav>

            <div class="modal fade notification-modal" id="mobileNotificationsModal" tabindex="-1" aria-labelledby="mobileNotificationsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen-lg-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mobileNotificationsModalLabel">
                                <i class="fas fa-bell me-2"></i>Notifications
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="notification-header d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Recent</span>
                                @if($unreadNotificationsCount > 0)
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none">Mark all as read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="notification-list">
                                @forelse($latestNotifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $title = $data['title'] ?? 'Notification';
                                        $message = $data['message'] ?? '';
                                        $openLabel = $data['action_label'] ?? 'Open';
                                    @endphp
                                    <div class="notification-item {{ $notification->read_at === null ? 'unread' : '' }}">
                                        <div class="notification-title">{{ $title }}</div>
                                        <div class="notification-body">{{ $message }}</div>
                                        <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                        <div class="notification-actions">
                                            <a href="{{ route('notifications.open', $notification->id) }}" class="btn btn-primary btn-sm">{{ $openLabel }}</a>
                                            @if($notification->read_at === null)
                                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm">Mark as Read</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="notification-item">
                                        <p class="text-muted mb-0 small">No notifications yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

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

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        {{ session('warning') }}
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
            const themeToggle = document.getElementById('themeToggle');
            const themeKey = 'lms_theme';

            if (!themeToggle) {
                return;
            }

            function updateThemeButton(theme) {
                const isDark = theme === 'dark';
                themeToggle.innerHTML = isDark
                    ? '<i class="fas fa-sun me-1"></i><span class="d-none d-sm-inline">Light</span>'
                    : '<i class="fas fa-moon me-1"></i><span class="d-none d-sm-inline">Dark</span>';
                themeToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            }

            function applyTheme(theme, persist = true) {
                const isDark = theme === 'dark';
                document.body.classList.toggle('dark-mode', isDark);
                updateThemeButton(theme);
                if (persist) {
                    try {
                        localStorage.setItem(themeKey, theme);
                    } catch (error) {
                        // Ignore storage failures and keep runtime theme only.
                    }
                }
            }

            let savedTheme = null;
            try {
                savedTheme = localStorage.getItem(themeKey);
            } catch (error) {
                savedTheme = null;
            }
            const initialTheme = savedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyTheme(initialTheme, false);

            themeToggle.addEventListener('click', function () {
                const nextTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
                applyTheme(nextTheme);
            });
        })();

        (function () {
            const notificationTrigger = document.getElementById('notificationDropdown');
            const mobileModalElement = document.getElementById('mobileNotificationsModal');
            const mobileQuery = window.matchMedia('(max-width: 991.98px)');

            if (!notificationTrigger || !mobileModalElement || typeof bootstrap === 'undefined') {
                return;
            }

            const mobileNotificationsModal = new bootstrap.Modal(mobileModalElement);

            notificationTrigger.addEventListener('click', function (event) {
                if (!mobileQuery.matches) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                if (typeof bootstrap.Dropdown !== 'undefined') {
                    const dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(notificationTrigger);
                    dropdownInstance.hide();
                }

                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                if (sidebar) {
                    sidebar.classList.remove('mobile-open');
                }
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
                document.body.classList.remove('overflow-hidden');

                mobileNotificationsModal.show();
            });
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
