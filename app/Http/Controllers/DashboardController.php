<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use App\Models\Module;
use App\Models\InformationSheet;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $recentAnnouncements = $this->getRecentAnnouncements(3);
        $recentAnnouncementsCount = $this->getRecentAnnouncementsCount();
        
        if (in_array($user->role, ['admin', 'instructor'])) {
            $data = $this->getAdminInstructorData($user);
            $data['recentAnnouncements'] = $recentAnnouncements;
            $data['recentAnnouncementsCount'] = $recentAnnouncementsCount;
            $data['unreadCount'] = 0;
            
            $data['totalStudents'] = $data['totalStudents'] ?? 0;
            $data['totalInstructors'] = $data['totalInstructors'] ?? 0;
            $data['totalModules'] = $data['totalModules'] ?? 0;
            $data['ongoingBatches'] = $data['ongoingBatches'] ?? 0;
            
            return view('dashboard', $data);
        } else {
            // Calculate student progress for the view
            $progressSummary = $this->getProgressSummary($user);
            $progressPercentage = $progressSummary['total_modules'] > 0 
                ? ($progressSummary['completed_modules'] / $progressSummary['total_modules']) * 100 
                : 0;
            
            $totalActivities = $progressSummary['total_modules'];
            $completedActivities = $progressSummary['completed_modules'];
            
            $data = [
                'recentAnnouncements' => $recentAnnouncements,
                'recentAnnouncementsCount' => $recentAnnouncementsCount,
                'unreadCount' => 0,
                // Add student progress data with proper naming
                'student_progress' => round($progressPercentage),
                'finished_activities' => $completedActivities . '/' . $totalActivities,
                'total_modules' => $progressSummary['total_modules'],
                'average_grade' => $progressSummary['average_score'] . '%'
            ];
            return view('dashboard', $data);
        }
    }

    // Add this method to get the count
    private function getRecentAnnouncementsCount()
    {
        return Announcement::where(function($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->count();
    }

    public function getStudentDashboardData()
    {
        $user = Auth::user();
        
        // Get student progress data
        $progressSummary = $this->getProgressSummary($user);
        
        // Calculate progress percentage
        $progressPercentage = $progressSummary['total_modules'] > 0 
            ? ($progressSummary['completed_modules'] / $progressSummary['total_modules']) * 100 
            : 0;
        
        // Calculate total activities (modules + information sheets)
        $totalActivities = $progressSummary['total_modules'] + $progressSummary['in_progress_modules'];
        $completedActivities = $progressSummary['completed_modules'];
        
        $data = [
            'progress' => round($progressPercentage),
            'finished_activities' => $completedActivities . '/' . $totalActivities,
            'total_modules' => $progressSummary['total_modules'],
            'average_grade' => $progressSummary['average_score']
        ];
        
        return response()->json($data);
    }

    private function getRecentAnnouncements($limit = 3)
    {
        return Announcement::with(['user', 'comments'])
            ->where(function($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    public function redirectToRoleDashboard()
    {
        $user = Auth::user();
        
        if (in_array($user->role, ['admin', 'instructor'])) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }

    public function getProgressData()
    {
        $user = Auth::user();
        
        // Get summary data
        $summary = $this->getProgressSummary($user);
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);
        
        // Get module progress
        $moduleProgress = $this->getModuleProgress($user);
        
        return response()->json([
            'summary' => $summary,
            'recentActivity' => $recentActivity,
            'moduleProgress' => $moduleProgress
        ]);
    }
    
    public function getProgressReport()
    {
        $user = Auth::user();
        
        $totalLearningTime = UserProgress::where('user_id', $user->id)
            ->sum('time_spent');
            
        $averageScore = UserProgress::where('user_id', $user->id)
            ->whereNotNull('score')
            ->avg('score');
            
        $completedModules = UserProgress::where('user_id', $user->id)
            ->where('progressable_type', Module::class)
            ->where('status', 'completed')
            ->count();
            
        $totalModules = Module::where('is_active', true)->count();
        $completionRate = $totalModules > 0 ? ($completedModules / $totalModules) * 100 : 0;
        
        return response()->json([
            'total_learning_time' => $this->formatTime($totalLearningTime),
            'average_score' => round($averageScore, 1),
            'completion_rate' => round($completionRate, 1)
        ]);
    }

    // NEW METHOD: Get data for admin and instructor dashboards
    private function getAdminInstructorData($user)
    {
        // Total Students (active students with role 'student')
        $totalStudents = User::where('role', 'student')
            ->where('stat', 1) // Assuming 'stat' = 1 means active
            ->count();

        // Total Instructors (only for admin)
        $totalInstructors = $user->role === 'admin' 
            ? User::where('role', 'instructor')->where('stat', 1)->count()
            : 0;

        // Total Modules (active modules)
        $totalModules = Module::where('is_active', true)->count();

        // Ongoing Batches/Sections (unique sections from class management)
        $ongoingBatches = User::where('role', 'student')
            ->whereNotNull('section')
            ->distinct('section')
            ->count('section');

        return [
            'totalStudents' => $totalStudents,
            'totalInstructors' => $totalInstructors,
            'totalModules' => $totalModules,
            'ongoingBatches' => $ongoingBatches
        ];
    }
    
    private function getProgressSummary($user)
    {
        $completedModules = UserProgress::where('user_id', $user->id)
            ->where('progressable_type', Module::class)
            ->where('status', 'completed')
            ->count();
            
        $inProgressModules = UserProgress::where('user_id', $user->id)
            ->where('progressable_type', Module::class)
            ->where('status', 'in_progress')
            ->count();
            
        $totalModules = Module::where('is_active', true)->count();
        
        $averageScore = UserProgress::where('user_id', $user->id)
            ->whereNotNull('score')
            ->avg('score') ?? 0;
            
        return [
            'completed_modules' => $completedModules,
            'in_progress_modules' => $inProgressModules,
            'total_modules' => $totalModules,
            'average_score' => round($averageScore, 1)
        ];
    }
    
    private function getRecentActivity($user)
    {
        $recentProgress = UserProgress::where('user_id', $user->id)
            ->with('progressable')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
            
        return $recentProgress->map(function($progress) {
            return [
                'type' => $this->getActivityType($progress),
                'title' => $this->getActivityTitle($progress),
                'timestamp' => $progress->updated_at->toISOString()
            ];
        });
    }
    
    private function getModuleProgress($user)
    {
        $modules = Module::where('is_active', true)->get();
        
        return $modules->map(function($module) use ($user) {
            $progress = $this->calculateModuleProgress($module, $user);
            
            return [
                'id' => $module->id,
                'name' => $module->module_name,
                'progress' => $progress['percentage'],
                'status' => $progress['status']
            ];
        })->filter(fn($module) => $module['progress'] > 0) // Only show modules with progress
          ->sortByDesc('progress')
          ->values();
    }
    
    private function calculateModuleProgress($module, $user)
    {
        $totalSheets = $module->informationSheets->count();
        
        if ($totalSheets === 0) {
            return ['percentage' => 0, 'status' => 'Not Started'];
        }
        
        $completedSheets = UserProgress::where('user_id', $user->id)
            ->where('progressable_type', InformationSheet::class)
            ->whereIn('progressable_id', $module->informationSheets->pluck('id'))
            ->where('status', 'completed')
            ->count();
            
        $percentage = ($completedSheets / $totalSheets) * 100;
        
        if ($percentage === 0) {
            $status = 'Not Started';
        } elseif ($percentage === 100) {
            $status = 'Completed';
        } else {
            $status = 'In Progress';
        }
        
        return [
            'percentage' => round($percentage),
            'status' => $status
        ];
    }
    
    private function getActivityType($progress)
    {
        if ($progress->progressable_type === Module::class) {
            return 'module_completed';
        } elseif ($progress->progressable_type === InformationSheet::class) {
            return 'sheet_completed';
        } elseif ($progress->status === 'passed') {
            return 'quiz_passed';
        } elseif ($progress->status === 'failed') {
            return 'quiz_failed';
        } else {
            return 'started';
        }
    }
    
    private function getActivityTitle($progress)
    {
        $progressable = $progress->progressable;
        
        if (!$progressable) {
            return 'Unknown Activity';
        }
        
        $progressableType = $progress->progressable_type;
        
        if ($progressableType === Module::class) {
            return "Completed module: {$progressable->module_name}";
        } elseif ($progressableType === InformationSheet::class) {
            return "Completed sheet: {$progressable->title}";
        } else {
            return "Updated progress";
        }
    }
    
    private function formatTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }
}