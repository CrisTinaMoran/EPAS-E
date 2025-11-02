<?php
namespace App\Http\Controllers;


use App\Models\Announcement;
use App\Models\AnnouncementComment;
use App\Models\User; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this line
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $announcements = Announcement::with(['user', 'comments.user', 'read_by_users'])
            ->forUser($user) // Use our new scope
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('private.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('private.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
            'is_urgent' => 'boolean',
            'publish_at' => 'nullable|date',
            'deadline' => 'nullable|date'
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'is_pinned' => $request->is_pinned ?? false,
            'is_urgent' => $request->is_urgent ?? false,
            'publish_at' => $request->publish_at,
            'deadline' => $request->deadline
        ]);

        return redirect()->route('private.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load(['user', 'comments.user']);
        return view('private.announcements.show', compact('announcement'));
    }

    public function addComment(Request $request, Announcement $announcement)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        AnnouncementComment::create([
            'announcement_id' => $announcement->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    public function getRecentAnnouncements()
    {
        $announcements = Announcement::with('user')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => Str::limit($announcement->content, 100),
                    'is_urgent' => $announcement->is_urgent,
                    'is_pinned' => $announcement->is_pinned,
                    'author' => $announcement->user->full_name ?? $announcement->user->name,
                    'created_at' => $announcement->created_at->diffForHumans(),
                    'url' => route('private.announcements.show', $announcement->id)
                ];
            });

        return response()->json($announcements);
    }

    public function markAsRead(Announcement $announcement)
    {
        try {
            // Assuming you have a many-to-many relationship for read_by_users
            $announcement->read_by_users()->syncWithoutDetaching([Auth::id()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Announcement marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking announcement as read: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as read'
            ], 500);
        }
    }

    public function getUnreadCount()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['count' => 0]);
            }

            // Get all announcements that are visible to the user
            $visibleAnnouncements = Announcement::forUser($user)->pluck('id');
            
            if ($visibleAnnouncements->isEmpty()) {
                return response()->json(['count' => 0]);
            }

            // Get announcements the user has already read
            $readAnnouncements = $user->read_announcements()->pluck('announcement_id');
            
            // Count unread announcements (visible but not read)
            $unreadCount = $visibleAnnouncements->diff($readAnnouncements)->count();

            return response()->json([
                'count' => $unreadCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }
    }

    public static function createAutomaticAnnouncement($type, $content, $user, $targetRoles = 'all')
    {
        $titleMap = [
            'module' => 'New Module Created',
            'information_sheet' => 'New Information Sheet Added', 
            'topic' => 'New Topic Published',
            'user_registered' => 'New User Registration',
            'user_approved' => 'User Account Approved',
            'comment' => 'New Comment Posted',
            'self_check' => 'New Self Check Available',
            'task_sheet' => 'New Task Sheet Added',
            'job_sheet' => 'New Job Sheet Available',
        ];

        $title = $titleMap[$type] ?? 'New Activity';

        return \App\Models\Announcement::create([
            'title' => $title,
            'content' => $content,
            'user_id' => $user->id,
            'is_pinned' => false,
            'is_urgent' => false,
            'target_roles' => $targetRoles
        ]);
    }
}