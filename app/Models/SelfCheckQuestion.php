<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfCheckQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'self_check_id',
        'question_text',
        'question_type',
        'points',
        'options',
        'correct_answer',
        'explanation',
        'order',
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
        'options' => 'array',
    ];

    public function selfCheck(): BelongsTo
    {
        return $this->belongsTo(SelfCheck::class);
    }

    public function getFormattedOptionsAttribute(): array
    {
        if (!$this->options) {
            return [];
        }

        $options = $this->options;
        if ($this->question_type === 'multiple_choice') {
            $formatted = [];
            foreach (['A', 'B', 'C', 'D', 'E'] as $index => $letter) {
                if (isset($options[$index])) {
                    $formatted[$letter] = $options[$index];
                }
            }
            return $formatted;
        }

        return $options;
    }

    public function getCorrectAnswerFormattedAttribute(): string
    {
        if ($this->question_type === 'multiple_choice') {
            $options = $this->formatted_options;
            return $options[$this->correct_answer] ?? $this->correct_answer;
        }

        return $this->correct_answer;
    }
}