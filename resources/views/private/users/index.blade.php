@extends('layouts.app')

@section('title', 'Users')

@section('content')
<link rel="stylesheet" href="{{ dynamic_asset('css/pages/index.css')}}">

<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">User Management</h5>
        <div>
            <span class="badge bg-primary">Total: {{ $users->total() }}</span>
            <span class="badge bg-info ms-1">Page: {{ $users->count() }}</span>
        </div>
    </div>

    <!-- Compact Search and Filter Row -->
    <div class="row align-items-center mb-3">
        <!-- Search - Takes most space -->
        <div class="col-md-8 col-lg-9">
            <form method="GET" action="{{ route('private.users.index') }}" id="searchForm">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="form-control search-input" 
                        placeholder="Search users..." value="{{ request('search') }}"
                        onkeypress="if(event.keyCode === 13) { this.form.submit(); }">
                </div>
                <input type="hidden" name="sort" value="{{ request('sort', '') }}">
                <input type="hidden" name="direction" value="{{ request('direction', '') }}">
                <input type="hidden" name="filter" value="{{ request('filter', '') }}">
            </form>
        </div>
        
        <!-- Filter Dropdown - Compact -->
        <div class="col-md-2 col-lg-2 text-center">
            <div class="filter-container">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter"></i>
                    @if(request()->has('filter'))
                        <span class="badge bg-primary ms-1">{{ substr(request('filter'), 0, 10) }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <li><a class="dropdown-item filter-option {{ !request()->has('filter') ? 'active' : '' }}" href="#" data-filter="all">
                        All Users <span class="badge bg-secondary float-end">{{ $filterCounts['total'] ?? $users->total() }}</span>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">By Role</h6></li>
                    <li><a class="dropdown-item filter-option {{ request('filter') == 'role=student' ? 'active' : '' }}" href="#" data-filter="role=student">
                        Students Only <span class="badge bg-secondary float-end">{{ $filterCounts['students'] ?? '' }}</span>
                    </a></li>
                    <li><a class="dropdown-item filter-option {{ request('filter') == 'role=instructor' ? 'active' : '' }}" href="#" data-filter="role=instructor">
                        Instructors Only <span class="badge bg-secondary float-end">{{ $filterCounts['instructors'] ?? '' }}</span>
                    </a></li>
                    <li><a class="dropdown-item filter-option {{ request('filter') == 'role=admin' ? 'active' : '' }}" href="#" data-filter="role=admin">
                        Admins Only <span class="badge bg-secondary float-end">{{ $filterCounts['admins'] ?? '' }}</span>
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">By Status</h6></li>
                    <li><a class="dropdown-item filter-option {{ request('filter') == 'status=pending' ? 'active' : '' }}" href="#" data-filter="status=pending">
                        Pending Approval <span class="badge bg-secondary float-end">{{ $filterCounts['pending'] ?? '' }}</span>
                    </a></li>
                    <li><a class="dropdown-item filter-option {{ request('filter') == 'status=active' ? 'active' : '' }}" href="#" data-filter="status=active">
                        Active Only <span class="badge bg-secondary float-end">{{ $filterCounts['active'] ?? '' }}</span>
                    </a></li>
                    <li><a class="dropdown-item filter-option {{ request('filter') == 'verified=no' ? 'active' : '' }}" href="#" data-filter="verified=no">
                        Unverified Email <span class="badge bg-secondary float-end">{{ $filterCounts['unverified'] ?? '' }}</span>
                    </a></li>
                </ul>
            </div>
        </div>
        
        <!-- Clear Filter - Compact X Button -->
        <div class="col-md-2 col-lg-1 text-end">
            @if(request()->has('filter') || request()->has('search') || request()->has('sort'))
                <a href="{{ route('private.users.index') }}" class="btn btn-outline-danger btn-sm clear-filter-btn" data-bs-toggle="tooltip" title="Clear all filters">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </div>
    <!-- Desktop Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th data-sort="id"># <i class="fas fa-sort"></i></th>
                        <th></th>
                        <th data-sort="first_name">Name <i class="fas fa-sort"></i></th>
                        <th data-sort="student_id">Student ID <i class="fas fa-sort"></i></th>
                        <th data-sort="email">Email <i class="fas fa-sort"></i></th>
                        <th data-sort="role">Role <i class="fas fa-sort"></i></th>
                        <th data-sort="department_id">Department <i class="fas fa-sort"></i></th>
                        <th data-sort="section">Section <i class="fas fa-sort"></i></th>
                        <th data-sort="room_number">Room <i class="fas fa-sort"></i></th>
                        <th data-sort="email_verified_at">Verified <i class="fas fa-sort"></i></th>
                        <th data-sort="stat">Status <i class="fas fa-sort"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td>
                                @php
                                    $firstName = $user->first_name ?? '';
                                    $lastName = $user->last_name ?? '';
                                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                    $avatarUrl = $user->profile_image 
                                        ? secure_asset('storage/profile-images/' . $user->profile_image)
                                        : "https://ui-avatars.com/api/?name={$initials}&background=007fc9&color=fff&size=32";
                                @endphp
                                <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle">
                            </td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->student_id ?? 'N/A' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($user->email, 15) }}</td>
                            <td><span class="badge bg-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'instructor' ? 'info' : 'secondary') }}">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ \Illuminate\Support\Str::limit($user->department->name ?? 'N/A', 12) }}</td>
                            <td>{{ $user->section ?? 'N/A' }}</td>
                            <td>{{ $user->room_number ?? 'N/A' }}</td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-warning text-dark">No</span>
                                @endif
                            </td>
                            <td>
                                @if($user->stat)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="action-buttons">                
                                @if(!$user->stat)
                                    <form action="{{ route('private.users.approve', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-success approve-btn"><i class="fas fa-check"></i></button>
                                    </form>
                                @endif

                                <a href="{{ route('private.users.edit', $user->id)}}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                
                                <form action="{{ route('private.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-3 text-muted">
                                <i class="fas fa-users fa-lg mb-2"></i>
                                <p class="mb-0">No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Expandable Profiles -->
    <div class="mobile-users-container">
        @forelse ($users as $user)
            <div class="mobile-profile-card" id="profile-{{ $user->id }}">
                <div class="mobile-profile-header" onclick="toggleProfile({{ $user->id }})">
                    <div class="mobile-profile-avatar">
                        @php
                            $firstName = $user->first_name ?? '';
                            $lastName = $user->last_name ?? '';
                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            $avatarUrl = $user->profile_image 
                                ? secure_asset('storage/profile-images/' . $user->profile_image)
                                : "https://ui-avatars.com/api/?name={$initials}&background=007fc9&color=fff&size=64";
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="{{ $user->full_name }}">
                    </div>
                    <div class="mobile-profile-info">
                        <div class="mobile-profile-name">{{ $user->full_name }}</div>
                        <div class="mobile-profile-role">{{ ucfirst($user->role) }}</div>
                        <div class="mobile-profile-badges">
                            @if($user->stat)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="mobile-profile-actions">
                        @if(!$user->stat)
                            <form action="{{ route('private.users.approve', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="button" class="btn btn-success approve-btn" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('private.users.edit', $user->id)}}" class="btn btn-outline-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form action="{{ route('private.users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <button class="mobile-expand-btn">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="mobile-profile-details">
                    <div class="mobile-details-content">
                        <div class="mobile-detail-grid">
                            <div class="mobile-detail-item">
                                <span class="mobile-detail-label">Email</span>
                                <span class="mobile-detail-value">{{ $user->email }}</span>
                            </div>
                            <div class="mobile-detail-item">
                                <span class="mobile-detail-label">Student ID</span>
                                <span class="mobile-detail-value">{{ $user->student_id ?? 'N/A' }}</span>
                            </div>
                            <div class="mobile-detail-item">
                                <span class="mobile-detail-label">Department</span>
                                <span class="mobile-detail-value">{{ $user->department->name ?? 'N/A' }}</span>
                            </div>
                            <div class="mobile-detail-item">
                                <span class="mobile-detail-label">Section</span>
                                <span class="mobile-detail-value">{{ $user->section ?? 'N/A' }}</span>
                            </div>
                            <div class="mobile-detail-item">
                                <span class="mobile-detail-label">Room</span>
                                <span class="mobile-detail-value">{{ $user->room_number ?? 'N/A' }}</span>
                            </div>
                            <div class="mobile-detail-item">
                                <span class="mobile-detail-label">Email Verified</span>
                                <span class="mobile-detail-value">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-warning text-dark">No</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">
                <i class="fas fa-users fa-2x mb-3"></i>
                <p>No users found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination-container">
            <div class="pagination-info">
                {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }}
            </div>
            
            <nav aria-label="User pagination">
                <ul class="pagination mb-0">
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">&raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">&raquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif
</div>

<script>
function toggleProfile(userId) {
    const profileCard = document.getElementById(`profile-${userId}`);
    profileCard.classList.toggle('expanded');
}

// Close other profiles when one is opened (optional)
function closeOtherProfiles(currentUserId) {
    document.querySelectorAll('.mobile-profile-card').forEach(card => {
        if (!card.id.includes(currentUserId)) {
            card.classList.remove('expanded');
        }
    });
}

// Add click event to close other profiles when one is opened
document.querySelectorAll('.mobile-profile-header').forEach(header => {
    header.addEventListener('click', function() {
        const profileCard = this.closest('.mobile-profile-card');
        const userId = profileCard.id.split('-')[1];
        closeOtherProfiles(userId);
    });
});
</script>
@endsection

@section('scripts')
<script src="{{ dynamic_asset('js/users/index.js') }}"></script>
@endsection