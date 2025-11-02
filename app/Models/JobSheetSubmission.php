<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSheetSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_sheet_id',
        'user_id',
        'completed_steps',
        'observations',
        'challenges',
        'solutions',
        'time_taken',
        'submitted_at',
        'evaluator_notes',
        'evaluated_at',
    ];

    protected $casts = [
        'completed_steps' => 'array',
        'submitted_at' => 'datetime',
        'evaluated_at' => 'datetime',
        'time_taken' => 'integer',
    ];

    public function jobSheet(): BelongsTo
    {
        return $this->belongsTo(JobSheet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function performanceCriteria()
    {
        return $this->morphOne(PerformanceCriteria::class, 'related');
    }

    public function getCompletedStepsArrayAttribute(): array
    {
        return $this->completed_steps ?? [];
    }

    public function getCompletionPercentageAttribute(): float
    {
        $totalSteps = $this->jobSheet->step_count;
        if ($totalSteps === 0) return 0;
        return (count($this->completed_steps_array) / $totalSteps) * 100;
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

    public function getEvaluationStatusAttribute(): string
    {
        if ($this->evaluated_at) return 'Evaluated';
        return 'Pending Evaluation';
    }
}