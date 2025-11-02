<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'course_id', // Add this
        'sector',
        'qualification_title',
        'unit_of_competency',
        'module_title',
        'module_number',
        'module_name',
        'table_of_contents',
        'how_to_use_cblm',
        'introduction',
        'learning_outcomes',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Add this relationship
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function informationSheets(): HasMany
    {
        return $this->hasMany(InformationSheet::class)->orderBy('order');
    }
}