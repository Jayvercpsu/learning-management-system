<nav class="nav flex-column">
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('admin.teachers') }}" class="nav-link {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i> Teachers
    </a>
    <a href="{{ route('admin.students') }}" class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>