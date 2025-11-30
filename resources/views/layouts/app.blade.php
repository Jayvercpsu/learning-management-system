<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS - Learning Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
        }
        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
            border-left: 4px solid #3498db;
        }
        .main-content {
            background: white;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 p-0 sidebar d-none d-md-block">
                <div class="p-4">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-graduation-cap"></i> LMS
                    </h4>
                    <div class="mb-4">
                        <small class="text-muted">{{ strtoupper(auth()->user()->role) }}</small>
                        <p class="mb-0">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                @yield('sidebar')
            </div>

            <div class="col-md-10 main-content">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-edit"></i> Edit Profile</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="p-4">
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>