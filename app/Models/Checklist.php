<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_sheet_id',
        'checklist_number',
        'title',
        'description',
        'items',
        'total_score',
        'max_score',
        'completed_by',
        'completed_at',
        'evaluated_by',
        'evaluated_at',
        'evaluator_notes',
    ];

    protected $casts = [
        'items' => 'array',
        'total_score' => 'integer',
        'max_score' => 'integer',
        'completed_at' => 'datetime',
        'evaluated_at' => 'datetime',
    ];

    public function informationSheet(): BelongsTo
    {
        return $this->belongsTo(InformationSheet::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function getItemsListAttribute(): array
    {
        return $this->items ?? [];
    }

    public function getAverageRatingAttribute(): float
    {
        $items = $this->items_list;
        if (empty($items)) return 0;
        
        $totalRating = array_sum(array_column($items, 'rating'));
        return $totalRating / count($items);
    }

    public function getRatingPercentageAttribute(): float
    {
        if ($this->max_score === 0) return 0;
        return ($this->total_score / $this->max_score) * 100;
    }

    public function getGradeAttribute(): string
    {
        $percentage = $this->rating_percentage;
        if ($percentage >= 90) return '5 - Excellent';
        if ($percentage >= 80) return '4 - Very Good';
        if ($percentage >= 70) return '3 - Good';
        if ($percentage >= 60) return '2 - Satisfactory';
        return '1 - Needs Improvement';
    }

    public function getCompletionStatusAttribute(): string
    {
        if ($this->evaluated_at) return 'Evaluated';
        if ($this->completed_at) return 'Completed';
        return 'Pending';
    }
}