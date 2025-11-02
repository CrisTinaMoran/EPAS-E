<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_sheet_id',
        'title',
        'content',
        'order'
    ];

    public function informationSheet()
    {
        return $this->belongsTo(InformationSheet::class);
    }

    public function getNextTopic()
    {
        return self::where('information_sheet_id', $this->information_sheet_id)
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }

    public function getPreviousTopic()
    {
        return self::where('information_sheet_id', $this->information_sheet_id)
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }
}