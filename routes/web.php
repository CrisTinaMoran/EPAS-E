<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\PrivateLoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentDashboard;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleContentController;
use App\Http\Controllers\TaskSheetController;
use App\Http\Controllers\JobSheetController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\PerformanceCriteriaController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InformationSheetController;
use App\Http\Controllers\SelfCheckController;
use App\Http\Controllers\TopicController;
use App\Services\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\LoginController;

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $request->user()->update(['email_verified_at' => now()]);
    
    return redirect('/login')->with('status', 'Email verified successfully! Your account is pending admin approval.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Lobby/Home Page Route
Route::get('/lobby', function () {
    return view('lobby');
})->name('lobby');

// Update the root route to point to lobby
Route::get('/', function () {
    return redirect()->route('lobby');
});

// PUBLIC ROUTES (No login required)
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Public Student Login & Registration Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Private Admin/instructor Login Routes
Route::get('/private/login', [PrivateLoginController::class, 'showLoginForm'])->name('private.login');
Route::post('/private/login', [PrivateLoginController::class, 'login']);

// Shared Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root based on auth status
Route::get('/', function () {
    if (Auth::check()) {
        return app(DashboardController::class)->redirectToRoleDashboard();
    }
    return redirect()->route('lobby');
});

Route::fallback(function () {
    if (Auth::check()) {
        return app(DashboardController::class)->redirectToRoleDashboard();
    }

    return redirect()->route('lobby');

});


// Protected Routes (requires login) - KEEP THESE INSIDE THE AUTH GROUP
Route::middleware(['auth'])->group(function () {
    // Role-based dashboard routing
    Route::get('/dashboard', [DashboardController::class, 'redirectToRoleDashboard'])->name('dashboard');
    
    // Student-specific dashboard
    Route::get('/student/dashboard', [StudentDashboard::class, 'index'])->name('student.dashboard');
    Route::get('/student/dashboard-data', [DashboardController::class, 'getStudentDashboardData'])->name('student.dashboard-data');
    Route::get('/student/progress-data', [StudentDashboard::class, 'getProgressData'])->name('student.progress-data');
    
    // Admin/instructor dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/progress-data', [DashboardController::class, 'getProgressData']);
    Route::get('/dashboard/progress-report', [DashboardController::class, 'getProgressReport']);

    // Dashboard data routes
    Route::get('/admin/dashboard-data', [DashboardController::class, 'getAdminDashboardData']);
    Route::get('/instructor/dashboard-data', [DashboardController::class, 'getInstructorDashboardData']);

    // User management routes (admin/instructor only)
    Route::middleware(['check.role:admin,instructor'])->group(function () {
        Route::get('private/users', [UserController::class, 'index'])->name('private.users.index');
        Route::get('private/users/create', [UserController::class, 'create'])->name('private.users.create');
        Route::post('private/users', [UserController::class, 'store'])->name('private.users.store');
        Route::get('private/users/{user}/edit', [UserController::class, 'edit'])->name('private.users.edit');
        Route::put('private/users/{user}', [UserController::class, 'update'])->name('private.users.update');
        Route::delete('private/users/{user}', [UserController::class, 'destroy'])->name('private.users.destroy');
        Route::post('private/users/{user}/approve', [UserController::class, 'approve'])->name('private.users.approve');

        // Role-specific user routes
        Route::get('private/students', [UserController::class, 'students'])->name('private.students.index');
        Route::get('private/instructors', [UserController::class, 'instructors'])->name('private.instructors.index');
        Route::get('private/admins', [UserController::class, 'admins'])->name('private.admins.index');
    });

        // Content Management Routes
        Route::get('/content-management', [CourseController::class, 'contentManagement'])->name('content.management');
        Route::resource('courses', CourseController::class);
        Route::resource('modules', ModuleController::class);

        // Information Sheet Routes
        Route::prefix('modules/{module}')->group(function () {
            Route::get('/information-sheets/create', [InformationSheetController::class, 'create'])->name('information-sheets.create');
            
            Route::post('/information-sheets', [InformationSheetController::class, 'store'])->name('information-sheets.store');
            Route::get('/information-sheets/{informationSheet}/edit', [InformationSheetController::class, 'edit'])->name('information-sheets.edit');
            Route::put('/information-sheets/{informationSheet}', [InformationSheetController::class, 'update'])->name('information-sheets.update');
            Route::delete('/information-sheets/{informationSheet}', [InformationSheetController::class, 'destroy'])->name('information-sheets.destroy');
            Route::get('/information-sheets/{informationSheet}', [ModuleController::class, 'showInformationSheet'])
                ->name('information-sheets.show');
        });

        // Topic Routes (inside information sheets)
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/topics/create', [TopicController::class, 'create'])->name('topics.create');
            Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
            Route::get('/topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
            Route::put('/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
        });

        // Add this OUTSIDE the prefix group - for delete only
        Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');
        Route::get('/module-content/{module}/{contentType}', [ModuleController::class, 'getContent'])->name('module.content');
        
        // Self Check Routes
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/self-checks/create', [SelfCheckController::class, 'create'])->name('self-checks.create');
            Route::post('/self-checks', [SelfCheckController::class, 'store'])->name('self-checks.store');
        });

        // Task Sheet Routes
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/task-sheets/create', [TaskSheetController::class, 'create'])->name('task-sheets.create');
            Route::post('/task-sheets', [TaskSheetController::class, 'store'])->name('task-sheets.store');
        });

        // Job Sheet Routes  
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/job-sheets/create', [JobSheetController::class, 'create'])->name('job-sheets.create');
            Route::post('/job-sheets', [JobSheetController::class, 'store'])->name('job-sheets.store');
        });

        // Homework Routes
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/homeworks/create', [HomeworkController::class, 'create'])->name('homeworks.create');
            Route::post('/homeworks', [HomeworkController::class, 'store'])->name('homeworks.store');
        });

        // Performance Criteria Routes
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/performance-criteria/create', [PerformanceCriteriaController::class, 'create'])->name('performance-criteria.create');
            Route::post('/performance-criteria', [PerformanceCriteriaController::class, 'store'])->name('performance-criteria.store');
        });

        // Checklist Routes
        Route::prefix('information-sheets/{informationSheet}')->group(function () {
            Route::get('/checklists/create', [ChecklistController::class, 'create'])->name('checklists.create');
            Route::post('/checklists', [ChecklistController::class, 'store'])->name('checklists.store');
        });

        // Module Content Routes
        Route::get('/module-content/{module}/{contentType}', [ModuleContentController::class, 'show'])
            ->name('module-content.show');
            
        Route::get('/api/module-content/{module}/{contentType}', [ModuleContentController::class, 'getContentApi'])
            ->name('api.module-content.show');

    // Module Content Routes
    Route::get('/module-content/{module}/{contentType}', [ModuleContentController::class, 'show'])
        ->name('module-content.show');
        
    Route::get('/api/module-content/{module}/{contentType}', [ModuleContentController::class, 'getContentApi'])
        ->name('api.module-content.show');

    // Topic Content Route
    Route::get('/topics/{topic}/content', [TopicController::class, 'getContent'])->name('topics.content');

    // Announcements Routes
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('private.announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('private.announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('private.announcements.store');
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('private.announcements.show');
    Route::post('/announcements/{announcement}/comment', [AnnouncementController::class, 'addComment'])->name('private.announcements.comment');
    Route::post('/announcements/{announcement}/read', [AnnouncementController::class, 'markAsRead'])->name('announcements.markAsRead');

    // API Routes for announcements
    Route::get('/api/announcements/recent', [AnnouncementController::class, 'getRecentAnnouncements']);
    Route::get('/api/announcements/unread-count', [AnnouncementController::class, 'getUnreadCount'])->name('api.announcements.unread-count');

    // Class Management Routes
    Route::get('/class-management', [ClassController::class, 'index'])->name('class-management.index');
    Route::get('/class-management/{section}', [ClassController::class, 'show'])->name('class-management.show');
    Route::post('/class-management/{section}/assign-adviser', [ClassController::class, 'assignAdviser'])->name('class-management.assign-adviser');
    Route::delete('/class-management/{section}/remove-adviser', [ClassController::class, 'removeAdviser'])->name('class-management.remove-adviser');
    
    // Instructor Management Route
    Route::get('/instructor', [UserController::class, 'instructor'])->name('instructor.index');
    
    // Profile routes
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::get('/storage/profile-images/{filename}', function ($filename) {
        $path = storage_path('app/public/profile-images/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    })->name('profile.image');

    // Self Check Routes
    Route::post('/information-sheets/{informationSheet}/self-checks', [ModuleController::class, 'storeSelfCheck'])->name('self-checks.store');
    Route::get('/self-checks/{selfCheck}/edit', [ModuleController::class, 'editSelfCheck'])->name('self-checks.edit');
    Route::put('/self-checks/{selfCheck}', [ModuleController::class, 'updateSelfCheck'])->name('self-checks.update');
    Route::delete('/self-checks/{selfCheck}', [ModuleController::class, 'destroySelfCheck'])->name('self-checks.destroy');

    // Progress tracking routes
    Route::get('/modules/{module}/progress', [ModuleController::class, 'getModuleProgress'])->name('modules.progress');
    Route::post('/self-checks/{selfCheck}/submit', [ModuleController::class, 'submitSelfCheck'])->name('self-checks.submit');
    Route::get('/modules/{module}/information-sheets/{informationSheet}/topics/{topic}', [ModuleController::class, 'showTopic'])->name('modules.topics.show');
});