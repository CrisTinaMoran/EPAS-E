<!-- Top Navbar -->
<header class="top-navbar">
    <!-- Left side - Hamburger and Title -->
    <div class="navbar-left">
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-brand">
            <h2>EPAS-E</h2>
            <p>Electronic Products Assembly and Servicing</p>
        </div>
    </div>

    <!-- Right side actions - Rounded container -->
    <div class="navbar-right">
        <!-- Dark Mode Toggle -->
        <div class="navbar-item">
            <button class="icon-button" id="dark-mode-toggle">
                <i class="fas fa-moon" id="dark-mode-icon"></i>
            </button>
        </div>

        <!-- Notifications -->
        <div class="navbar-item">
            <button class="icon-button" id="notifications-btn">
                <i class="fas fa-bell"></i>
                @if(isset($recentAnnouncementsCount) && $recentAnnouncementsCount > 0)
                    <span class="notification-badge" id="notification-badge">
                        {{ $recentAnnouncementsCount }}
                    </span>
                @endif
            </button>
            <div class="popover notifications-popover" id="notifications-popover">
                <div class="popover-header">
                    <span>Recent Announcements</span>
                    <div class="notification-filters">
                        <select id="notification-sort" class="form-select form-select-sm">
                            <option value="newest">Newest First</option>
                            <option value="deadline">Closest Deadline</option>
                            <option value="unread">Unread First</option>
                        </select>
                    </div>
                    <a href="{{ route('private.announcements.index') }}" class="view-all-link">View All</a>
                </div>
                <div class="notifications-list" id="notifications-list">
                    @php
                        $notifications = isset($recentAnnouncements) ? $recentAnnouncements : collect();
                    @endphp

                    @if($notifications->count() > 0)
                        @foreach($notifications as $announcement)
                            @php
                                // Safely check if read
                                $isRead = $announcement->isReadByUser(Auth::user());
                            @endphp
                            <div class="notification-item {{ $announcement->is_urgent ? 'urgent' : '' }} {{ $isRead ? 'read' : 'unread' }}"
                                data-announcement-id="{{ $announcement->id }}"
                                data-is-read="{{ $isRead ? '1' : '0' }}">
                                <a href="{{ route('private.announcements.show', $announcement->id) }}" 
                                class="notification-link"
                                onclick="markAsRead(event, {{ $announcement->id }})">
                                    <div class="notification-dot {{ $isRead ? 'read-dot' : 'unread-dot' }} {{ $announcement->is_urgent ? 'urgent-dot' : '' }}"></div>
                                    <div class="notification-content">
                                        <div class="notification-title">
                                            @if($announcement->is_pinned)
                                                <i class="fas fa-thumbtack text-warning me-1"></i>
                                            @endif
                                            {{ Str::limit($announcement->title, 40) }}
                                            @if($announcement->is_urgent)
                                                <span class="badge bg-danger ms-1">URGENT</span>
                                            @endif
                                        </div>
                                        <div class="notification-message">
                                            {{ Str::limit($announcement->content, 60) }}
                                        </div>
                                        <div class="notification-meta">
                                            <span class="notification-time">
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="notification-item empty">
                            <div class="notification-content text-center py-3">
                                <i class="fas fa-bell-slash text-muted mb-2"></i>
                                <div class="text-muted">No announcements</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User menu -->
        <div class="navbar-item">
            <button class="user-button" id="user-menu-btn">
                <div class="avatar">
                    @php
                        $user = Auth::user();
                        $firstName = $user->first_name ?? '';
                        $lastName = $user->last_name ?? '';
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                        $avatarUrl = $user->profile_image 
                            ? dynamic_asset('storage/profile-images/' . $user->profile_image)
                            : "https://ui-avatars.com/api/?name={$initials}&background=007fc9&color=fff&size=80";
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="User Avatar" id="navbar-avatar">
                    <span class="avatar-fallback" id="navbar-fallback">{{ $initials }}</span>
                </div>
            </button>
            <div class="dropdown" id="user-dropdown">
                <div class="dropdown-content">
                    <div class="dropdown-header" id="dropdown-username">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('private.users.edit', Auth::id()) }}" class="dropdown-item">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </a>
                    <a href="{{ route('about') }}" class="dropdown-item">
                        <i class="fas fa-info-circle"></i>
                        About
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item text-danger" id="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Logout Form -->
<form id="logout-form" action="{{ dynamic_route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<link rel="stylesheet" href="{{ dynamic_asset('css/layout/header.css') }}">