@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('sidebar')
@include ('admin.sidebar')
@endsection

@section('content')
<h2 class="mb-4">Admin Dashboard</h2>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Teachers</h6>
                        <h2 class="mb-0">{{ $stats['total_teachers'] }}</h2>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Pending Approvals</h6>
                        <h2 class="mb-0">{{ $stats['pending_teachers'] }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Students</h6>
                        <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Topics</h6>
                        <h2 class="mb-0">{{ $stats['total_topics'] }}</h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Videos</h6>
                        <h2 class="mb-0">{{ $stats['total_videos'] }}</h2>
                    </div>
                    <i class="fas fa-video fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Quizzes</h6>
                        <h2 class="mb-0">{{ $stats['total_quizzes'] }}</h2>
                    </div>
                    <i class="fas fa-question-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
</div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-7 p-4 text-white">
                        <h3 class="mb-3" style="font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                            <i class="fas fa-shapes me-2"></i>Geometry Learning Platform Overview
                        </h3>
                        <p class="mb-4" style="font-size: 1.05rem; line-height: 1.6; opacity: 0.95;">
                            A comprehensive platform integrating GeoGebra for interactive geometry education. Monitor and support teachers and students as they explore geometric concepts through dynamic visualizations.
                        </p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-bookmark me-2"></i>Postulate</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">A statement accepted as true without proof, forming the foundation of geometric reasoning.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-certificate me-2"></i>Theorem</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">A proven mathematical statement derived from postulates and previously proven theorems.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-drafting-compass me-2"></i>Construction</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">Creating geometric figures using compass and straightedge, or digitally with GeoGebra tools.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                                    <h6 class="mb-2" style="font-weight: 600;"><i class="fas fa-ruler-combined me-2"></i>Congruence</h6>
                                    <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">When two figures have the same shape and size, with corresponding parts equal.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://www.geogebra.org/" target="_blank" class="btn btn-light px-4" style="border-radius: 25px; font-weight: 600;">
                                <i class="fas fa-external-link-alt me-2"></i>Visit GeoGebra
                            </a>
                            <a href="{{ route('admin.teachers') }}" class="btn btn-outline-light px-4" style="border-radius: 25px; font-weight: 600; border: 2px solid white;">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-5 p-4" style="background: rgba(0,0,0,0.2);">
                        <h5 class="text-white mb-3" style="font-weight: 600;">
                            <i class="fas fa-play-circle me-2"></i>Tutorial: Platform Overview
                        </h5>
                        <div class="ratio ratio-16x9 mb-3" style="border-radius: 10px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                         <iframe src="https://www.youtube.com/embed/rEEAu5oAGUg" title="GeoGebra Tutorial - How to Use GeoGebra" allowfullscreen style="border: none;"></iframe>
                        </div>
                        
                        <div class="p-3 mb-3" style="background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                            <h6 class="text-white mb-2" style="font-weight: 600;"><i class="fas fa-cogs me-2"></i>Platform Features</h6>
                            <ul class="text-white mb-0" style="font-size: 0.9rem; opacity: 0.9; line-height: 1.8;">
                                <li>Manage teachers and students</li>
                                <li>Monitor learning resources</li>
                                <li>Track platform engagement</li>
                                <li>Support educational growth</li>
                            </ul>
                        </div>

                        <div class="text-center">
                            <a href="#" class="btn btn-light btn-sm px-3" style="border-radius: 20px; font-weight: 600;">
                                <i class="fas fa-video me-2"></i>More Tutorials
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection