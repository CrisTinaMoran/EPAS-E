<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSheetSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_sheet_id',
        'user_id',
        'findings',
        'observations',
        'challenges',
        'time_taken',
        'submitted_at',
    ];

    protected $casts = [
        'findings' => 'array',
        'submitted_at' => 'datetime',
        'time_taken' => 'integer',
    ];

    public function taskSheet(): BelongsTo
    {
        return $this->belongsTo(TaskSheet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function performanceCriteria()
    {
        return $this->morphOne(PerformanceCriteria::class, 'related');
    }

    public function getFindingsArrayAttribute(): array
    {
        return $this->findings ?? [];
    }

    public function getTimeTakenFormattedAttribute(): string
    {
        if (!$this->time_taken) return 'N/A';
        
        $hours = floor($this->time_taken / 60);
        $minutes = $this->time_taken % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        return "{$minutes}m";
    }
}