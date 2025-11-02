<?php

namespace App\Http\View\Composers;

use App\Models\Announcement;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AnnouncementComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Get recent announcements for this user
            $recentAnnouncements = Announcement::with(['user', 'read_by_users'])
                ->forUser($user)
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Count unread announcements
            $unreadCount = Announcement::forUser($user)
                ->whereDoesntHave('read_by_users', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();

            $view->with([
                'recentAnnouncements' => $recentAnnouncements,
                'recentAnnouncementsCount' => $unreadCount,
            ]);
        } else {
            $view->with([
                'recentAnnouncements' => collect(),
                'recentAnnouncementsCount' => 0,
            ]);
        }
    }
}