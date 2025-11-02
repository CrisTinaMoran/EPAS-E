<!-- Left Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <!-- User Profile Card -->
        <div class="sidebar-profile">
            <div class="profile-inner">
                @php
                    $user = Auth::user();
                    $firstName = $user->first_name ?? '';
                    $lastName = $user->last_name ?? '';
                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    $avatarUrl = $user->profile_image 
                        ? dynamic_asset('storage/profile-images/' . $user->profile_image)
                        : "https://ui-avatars.com/api/?name={$initials}&background=007fc9&color=fff&size=100";
                @endphp

                <form id="avatar-form" action="{{ dynamic_route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" id="avatar-upload" name="avatar" accept="image/png, image/jpeg">
                </form>

                <div class="avatar" data-tooltip="User Profile" onclick="document.getElementById('avatar-upload').click()">
                    <img src="{{ $avatarUrl }}" alt="User Avatar" id="sidebar-avatar">
                    <span class="avatar-fallback" id="sidebar-fallback">{{ $initials }}</span>
                </div>
                
                <div class="profile-info">
                    <h3 id="sidebar-username">{{ $user->first_name }} {{ $user->last_name }}</h3>
                    <p class="profile-role" id="sidebar-role">{{ ucfirst($user->role) }}</p>
                </div>
                
                <div class="profile-id">
                    @if($user->student_id)
                        ID: <span id="sidebar-employee-id">{{ $user->student_id }}</span>
                    @else
                        ID: <span id="sidebar-employee-id">{{ $user->id }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="sidebar-section">
            <div class="sidebar-label">Main Menu</div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- Add Announcements Link -->
                <a href="{{ route('private.announcements.index') }}" class="nav-item {{ Request::is('announcements*') ? 'active' : '' }}" data-tooltip="Announcements">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
                
                <a href="{{ route('courses.index') }}" class="nav-item {{ Request::is('courses*') ? 'active' : '' }}" data-tooltip="Courses">
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>

                <a href="#" class="nav-item {{ Request::is('grades*') ? 'active' : '' }}" data-tooltip="Grades">
                    <i class="fas fa-chart-line"></i>
                    <span>Grades</span>
                </a>

                <a href="#" class="nav-item {{ Request::is('analytics*') ? 'active' : '' }}" data-tooltip="Analytics">
                    <i class="fas fa-chart-pie"></i>
                    <span>Analytics</span>
                </a>
            </nav>
        </div>

        <!-- Content Management for Admin and Instructors -->
        @if(in_array(strtolower(Auth::user()->role), ['admin', 'instructor']))
        <div class="sidebar-section">
            <div class="sidebar-label">Content Management</div>
            <nav class="sidebar-nav">
                <a href="{{ route('content.management') }}" class="nav-item {{ Request::is('content-management*') ? 'active' : '' }}" data-tooltip="Content Management">
                    <i class="fas fa-cubes"></i>
                    <span>Content Management</span>
                </a>
            </nav>
        </div>
        @endif

        <!-- For Admin -->
        @if(strtolower(Auth::user()->role) === 'admin')
        <div class="sidebar-section">
            <div class="sidebar-label">Administration</div>
            <nav class="sidebar-nav">
                <a href="{{ route('private.users.index') }}" class="nav-item {{ Request::is('users*') && !Request::is('instructors*') && !Request::is('students*') && !Request::is('admins*') ? 'active' : '' }}" data-tooltip="All Users">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>

                <a href="{{ route('private.students.index') }}" class="nav-item {{ Request::is('students*') ? 'active' : '' }}" data-tooltip="Students">
                    <i class="fas fa-user-graduate"></i>
                    <span>Student Management</span>
                </a>

                <a href="{{ route('private.instructors.index') }}" class="nav-item {{ Request::is('instructors*') ? 'active' : '' }}" data-tooltip="instructors">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Instructor Management</span>
                </a>

                <a href="{{ route('class-management.index') }}" class="nav-item {{ Request::is('class-management*') ? 'active' : '' }}" data-tooltip="Class Management">
                    <i class="fas fa-chalkboard"></i>
                    <span>Class Management</span>
                </a>
            </nav>
        </div>
        @endif

        <!-- For Instructors -->
        @if(in_array(strtolower(Auth::user()->role), ['instructor']))
        <div class="sidebar-section">
            <div class="sidebar-label">Instructor Tools</div>
            <nav class="sidebar-nav">
                <a href="{{ route('class-management.index') }}" class="nav-item {{ Request::is('class-management*') ? 'active' : '' }}" data-tooltip="Class Management">
                    <i class="fas fa-chalkboard"></i>
                    <span>Class Management</span>
                </a>
            
                <a href="{{ route('private.students.index') }}" class="nav-item {{ Request::is('students*') ? 'active' : '' }}" data-tooltip="Students">
                    <i class="fas fa-user-graduate"></i>
                    <span>Student Management</span>
                </a>
            </nav>
        </div>
        @endif

        <!-- Help & Support -->
        <div class="sidebar-section">
            <nav class="sidebar-nav">
                <a href="#" class="nav-item {{ Request::is('help*') || Request::is('support*') ? 'active' : '' }}" data-tooltip="Help & Support">
                    <i class="fas fa-question-circle"></i>
                    <span>Help & Support</span>
                </a>
            </nav>
        </div>
    </div>
</aside>
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>