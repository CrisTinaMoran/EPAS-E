<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_sheet_id',
        'homework_number',
        'title',
        'description',
        'instructions',
        'requirements',
        'submission_guidelines',
        'reference_images',
        'due_date',
        'max_points',
        'allow_late_submission',
        'late_penalty',
    ];

    protected $casts = [
        'requirements' => 'array',
        'submission_guidelines' => 'array',
        'reference_images' => 'array',
        'due_date' => 'datetime',
        'max_points' => 'integer',
        'allow_late_submission' => 'boolean',
        'late_penalty' => 'integer',
    ];

    public function informationSheet(): BelongsTo
    {
        return $this->belongsTo(InformationSheet::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    public function getRequirementsListAttribute(): array
    {
        return $this->requirements ?? [];
    }

    public function getSubmissionGuidelinesListAttribute(): array
    {
        return $this->submission_guidelines ?? [];
    }

    public function getReferenceImagesListAttribute(): array
    {
        return $this->reference_images ?? [];
    }

    public function getSubmissionCountAttribute(): int
    {
        return $this->submissions()->count();
    }

    public function getLateSubmissionCountAttribute(): int
    {
        return $this->submissions()->where('is_late', true)->count();
    }

    public function getAverageScoreAttribute(): ?float
    {
        $submissions = $this->submissions()->whereNotNull('score')->get();
        if ($submissions->isEmpty()) {
            return null;
        }
        return $submissions->avg('score');
    }

    public function getIsPastDueAttribute(): bool
    {
        return now()->greaterThan($this->due_date);
    }

    public function getDaysUntilDueAttribute(): int
    {
        return now()->diffInDays($this->due_date, false);
    }
}