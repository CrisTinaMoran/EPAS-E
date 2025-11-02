<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_sheet_id',
        'task_number',
        'title',
        'description',
        'instructions',
        'objectives',
        'materials',
        'safety_precautions',
        'image_path',
        'estimated_duration',
        'difficulty_level',
    ];

    protected $casts = [
        'objectives' => 'array',
        'materials' => 'array',
        'safety_precautions' => 'array',
        'estimated_duration' => 'integer',
    ];

    public function informationSheet(): BelongsTo
    {
        return $this->belongsTo(InformationSheet::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TaskSheetItem::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSheetSubmission::class);
    }

    public function performanceCriteria()
    {
        return $this->morphOne(PerformanceCriteria::class, 'related');
    }

    public function getObjectivesListAttribute(): array
    {
        return $this->objectives ?? [];
    }

    public function getMaterialsListAttribute(): array
    {
        return $this->materials ?? [];
    }

    public function getSafetyPrecautionsListAttribute(): array
    {
        return $this->safety_precautions ?? [];
    }

    public function getAverageCompletionTimeAttribute(): ?float
    {
        $submissions = $this->submissions()->whereNotNull('submitted_at')->get();
        if ($submissions->isEmpty()) {
            return null;
        }
        return $submissions->avg('time_taken');
    }
}