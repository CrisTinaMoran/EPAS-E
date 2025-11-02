<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_sheet_id',
        'job_number',
        'title',
        'description',
        'objectives',
        'tools_required',
        'safety_requirements',
        'reference_materials',
        'estimated_duration',
        'difficulty_level',
    ];

    protected $casts = [
        'objectives' => 'array',
        'tools_required' => 'array',
        'safety_requirements' => 'array',
        'reference_materials' => 'array',
        'estimated_duration' => 'integer',
    ];

    public function informationSheet(): BelongsTo
    {
        return $this->belongsTo(InformationSheet::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(JobSheetStep::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(JobSheetSubmission::class);
    }

    public function performanceCriteria()
    {
        return $this->morphOne(PerformanceCriteria::class, 'related');
    }

    public function getObjectivesListAttribute(): array
    {
        return $this->objectives ?? [];
    }

    public function getToolsRequiredListAttribute(): array
    {
        return $this->tools_required ?? [];
    }

    public function getSafetyRequirementsListAttribute(): array
    {
        return $this->safety_requirements ?? [];
    }

    public function getReferenceMaterialsListAttribute(): array
    {
        return $this->reference_materials ?? [];
    }

    public function getStepCountAttribute(): int
    {
        return $this->steps()->count();
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