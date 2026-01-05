<nav class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('student.topics') }}" class="nav-link {{ request()->routeIs('student.topics') ? 'active' : '' }}">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('student.videos') }}" class="nav-link {{ request()->routeIs('student.videos*') ? 'active' : '' }}">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="{{ route('student.geogebra') }}" class="nav-link {{ request()->routeIs('student.geogebra') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> GeoGebra
    </a>
    <a href="{{ route('student.quizzes') }}" class="nav-link {{ request()->routeIs('student.quizzes') ? 'active' : '' }}">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('student.quizzes.results') }}" class="nav-link {{ request()->routeIs('student.quizzes.results') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>