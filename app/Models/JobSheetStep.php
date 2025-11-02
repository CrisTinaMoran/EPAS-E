<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSheetStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_sheet_id',
        'step_number',
        'instruction',
        'expected_outcome',
        'image_path',
        'warnings',
        'tips',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'warnings' => 'array',
        'tips' => 'array',
    ];

    public function jobSheet(): BelongsTo
    {
        return $this->belongsTo(JobSheet::class);
    }

    public function getWarningsListAttribute(): array
    {
        return $this->warnings ?? [];
    }

    public function getTipsListAttribute(): array
    {
        return $this->tips ?? [];
    }
}