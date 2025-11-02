<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'is_pinned',
        'is_urgent',
        'publish_at',
        'deadline',
        'target_roles' // Add this
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_urgent' => 'boolean',
        'publish_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AnnouncementComment::class)->orderBy('created_at', 'asc');
    }

    public function read_by_users()
    {
        return $this->belongsToMany(User::class, 'announcement_user')
                    ->withTimestamps()
                    ->withPivot('read_at');
    }

    public function markAsRead($userId = null)
    {
        $userId = $userId ?: auth()->id();
        
        if (!$userId) return false;
        
        return $this->read_by_users()->syncWithoutDetaching([$userId => ['read_at' => now()]]);
    }

    public function scopePublished($query)
    {
        return $query->where(function($q) {
            $q->whereNull('publish_at')
              ->orWhere('publish_at', '<=', now());
        });
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    // Add this method to filter by user role
    public function scopeForUser($query, $user)
    {
        return $query->where(function($q) use ($user) {
            $q->where('target_roles', 'all')
              ->orWhere('target_roles', 'like', "%{$user->role}%");
        });
    }

    // Safe method to check if user has read the announcement
    public function isReadByUser($user)
    {
        if (!$this->relationLoaded('read_by_users')) {
            return false;
        }
        
        return $this->read_by_users->contains($user->id);
    }
}