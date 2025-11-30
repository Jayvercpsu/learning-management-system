@extends('layouts.app')

@section('title', 'GeoGebra Interactive Graphing')

@section('sidebar')
<nav class="nav flex-column">
    <a href="{{ route('student.dashboard') }}" class="nav-link">
        <i class="fas fa-dashboard"></i> Dashboard
    </a>
    <a href="{{ route('student.topics') }}" class="nav-link">
        <i class="fas fa-book"></i> Topics
    </a>
    <a href="{{ route('student.videos') }}" class="nav-link">
        <i class="fas fa-video"></i> Videos
    </a>
    <a href="{{ route('student.geogebra') }}" class="nav-link active">
        <i class="fas fa-chart-line"></i> GeoGebra
    </a>
    <a href="{{ route('student.quizzes') }}" class="nav-link">
        <i class="fas fa-question-circle"></i> Quizzes
    </a>
    <a href="{{ route('student.quizzes.results') }}" class="nav-link">
        <i class="fas fa-chart-bar"></i> My Results
    </a>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">
            <i class="fas fa-chart-line"></i> GeoGebra Interactive Graphing
        </h4>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle"></i> Use the interactive graphing tool below to explore mathematical concepts, create graphs, and visualize functions.
        </div>

        <div id="geogebra-app" style="width: 100%; height: 600px; border: 1px solid #ddd; border-radius: 5px;"></div>

        <div class="mt-4">
            <h5>Quick Tips:</h5>
            <ul>
                <li>Use the toolbar on the left to select different tools</li>
                <li>Click and drag to create points, lines, and shapes</li>
                <li>Use the input bar at the bottom to enter equations</li>
                <li>Right-click on objects to access more options</li>
                <li>Use the zoom controls to adjust your view</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.geogebra.org/apps/deployggb.js"></script>
<script>
    const parameters = {
        "appName": "graphing",
        "width": document.getElementById('geogebra-app').offsetWidth,
        "height": 600,
        "showToolBar": true,
        "showAlgebraInput": true,
        "showMenuBar": true,
        "enableShiftDragZoom": true,
        "enableRightClick": true
    };
    
    const applet = new GGBApplet(parameters, true);
    applet.inject('geogebra-app');
</script>
@endpush
