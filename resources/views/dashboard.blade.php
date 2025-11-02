@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">
                @if(strtolower(Auth::user()->role) === 'admin')
                    Admin Dashboard
                @elseif(strtolower(Auth::user()->role) === 'instructor')
                    Instructor Dashboard
                @else
                    Student Dashboard
                @endif
            </h1>
        </div>
    </div>

    <!-- Role-Specific Dashboard Content -->
    @if(strtolower(Auth::user()->role) === 'admin')
        <!-- Admin Dashboard (unchanged) -->
        <div class="row mb-4">
            <!-- Overview Cards -->
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-primary" style="font-size: 24px; font-weight: bold;">
                            {{ $totalStudents ?? 0 }}
                        </div>
                        <div class="card-counter-label">Total Students</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-info" style="font-size: 24px; font-weight: bold;">
                            {{ $totalInstructors ?? 0 }}
                        </div>
                        <div class="card-counter-label">Total Instructors</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-success" style="font-size: 24px; font-weight: bold;">
                            {{ $totalModules ?? 0 }}
                        </div>
                        <div class="card-counter-label">Total Modules</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-warning" style="font-size: 24px; font-weight: bold;">
                            {{ $ongoingBatches ?? 0 }}
                        </div>
                        <div class="card-counter-label">Ongoing Batches</div>
                    </div>
                </div>
            </div>
        </div>

    @elseif(strtolower(Auth::user()->role) === 'instructor')
        <!-- Instructor Dashboard -->
        <div class="row mb-4">
            <!-- Overview Cards -->
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-primary" style="font-size: 24px; font-weight: bold;">
                            {{ $totalStudents ?? 0 }}
                        </div>
                        <div class="card-counter-label">Total Students</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-success" style="font-size: 24px; font-weight: bold;">
                            {{ $totalModules ?? 0 }}
                        </div>
                        <div class="card-counter-label">Total Modules</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="card-counter text-warning" style="font-size: 24px; font-weight: bold;">
                            {{ $ongoingBatches ?? 0 }}
                        </div>
                        <div class="card-counter-label">Ongoing Batches</div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Student Dashboard -->
        <!-- Progress Overview -->
        <div class="row mb-4">
            <!-- Student Progress -->
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Student Progress</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="progress-circle mx-auto">
                            <div class="card-counter text-primary" id="student-progress-text" 
                                data-progress="{{ $student_progress ?? 0 }}"
                                style="font-size: 24px; font-weight: bold;">
                                {{ $student_progress ?? 0 }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Finished Activities -->
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Finished Activities</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h4 class="card-counter text-success" id="finished-activities" 
                            data-activities="{{ $finished_activities ?? '0/0' }}"
                            style="font-size: 24px; font-weight: bold;">
                            {{ $finished_activities ?? '0/0' }}
                        </h4>
                        <p class="text-muted">Completed Activities</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Modules -->
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Modules</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="grade-display">
                            <h1 class="card-counter text-info" id="total-modules-count" 
                                data-modules="{{ $total_modules ?? 0 }}"
                                style="font-size: 24px; font-weight: bold;">
                                {{ $total_modules ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Grade -->
            <div class="col-md-3 mb-4">
                <div class="card text-center h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Average Grade</h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="grade-display">
                            <h1 class="card-counter text-warning" id="average-grade" 
                                data-grade="{{ $average_grade ?? '0%' }}"
                                style="font-size: 24px; font-weight: bold;">
                                {{ $average_grade ?? '0%' }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Announcements Card - For ALL Roles -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bullhorn me-2"></i>Latest Announcements
                    </h5>
                    <div>
                        <a href="{{ route('private.announcements.index') }}" class="btn btn-sm btn-outline-primary me-2">View All</a>
                        @if(in_array(Auth::user()->role, ['admin', 'instructor']))
                            <a href="{{ route('private.announcements.create') }}" class="btn btn-sm btn-primary">Create New</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div id="dashboard-announcements">
                        @if($recentAnnouncements->count() > 0)
                            @foreach($recentAnnouncements as $announcement)
                                <div class="announcement-item mb-4 p-3 border rounded {{ $announcement->is_urgent ? 'border-danger bg-light-danger' : '' }}">
                                    <div class="announcement-header d-flex justify-content-between align-items-start mb-2">
                                        <div class="announcement-title">
                                            <h6 class="mb-1">
                                                @if($announcement->is_pinned)
                                                    <i class="fas fa-thumbtack text-warning me-1" title="Pinned"></i>
                                                @endif
                                                <a href="{{ route('private.announcements.show', $announcement->id) }}" 
                                                class="text-decoration-none {{ $announcement->is_urgent ? 'text-danger fw-bold' : 'text-dark' }}">
                                                    {{ $announcement->title }}
                                                </a>
                                            </h6>
                                        </div>
                                        <div>
                                            @if($announcement->is_urgent)
                                                <span class="badge bg-danger">URGENT</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="announcement-content mb-2">
                                        <p class="mb-2 text-muted">{{ Str::limit($announcement->content, 150) }}</p>
                                    </div>
                                    
                                    <div class="announcement-footer d-flex justify-content-between align-items-center">
                                        <div class="announcement-meta">
                                            <small class="text-muted">
                                                By {{ $announcement->user->full_name ?? $announcement->user->name }}
                                                • {{ $announcement->created_at->diffForHumans() }}
                                                • {{ $announcement->comments->count() }} comments
                                            </small>
                                        </div>
                                        <div>
                                            <a href="{{ route('private.announcements.show', $announcement->id) }}" 
                                            class="btn btn-sm btn-outline-primary">
                                                View & Comment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-bullhorn fa-2x mb-3"></i>
                                <p>No announcements yet</p>
                                @if(in_array(Auth::user()->role, ['admin', 'instructor']))
                                    <a href="{{ route('private.announcements.create') }}" class="btn btn-sm btn-primary">
                                        Create First Announcement
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style rel="stylesheet" href="{{ dynamic_asset('css/components/announcement.css') }}"></style>
@if(strtolower(Auth::user()->role) === 'student')
<style rel="stylesheet" href="{{ dynamic_asset('css/dashboard.css') }}"></style>
<script src="{{ dynamic_asset('js/dashboard.js') }}"></script>
<script src="{{ dynamic_asset('js/functions/announcement.js') }}"></script>
@endif

@endsection