<nav class="nav flex-column">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('teacher.topics.index') }}" class="nav-link {{ request()->routeIs('teacher.topics*') ? 'active' : '' }}">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('teacher.videos.index') }}" class="nav-link {{ request()->routeIs('teacher.videos*') ? 'active' : '' }}">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="{{ route('teacher.geogebra') }}" class="nav-link {{ request()->routeIs('teacher.geogebra*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> GeoGebra
    </a>
    <a href="{{ route('teacher.quizzes.index') }}" class="nav-link {{ request()->routeIs('teacher.quizzes*') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('teacher.students') }}" class="nav-link {{ request()->routeIs('teacher.students*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i> Students
    </a>
</nav>