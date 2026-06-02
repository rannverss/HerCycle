<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mood extends Model
{
    protected $fillable = ['user_id', 'date', 'mood', 'level'];

    protected $casts = [
        'date' => 'date',
        'level' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get emoji for mood type
     */
    public function getEmojiAttribute(): string
    {
        return match ($this->mood) {
            'happy' => '😊',
            'neutral' => '😐',
            'angry' => '😡',
            default => '😐',
        };
    }

    /**
     * Get label for mood type
     */
    public function getLabelAttribute(): string
    {
        return match ($this->mood) {
            'happy' => 'Happy',
            'neutral' => 'Neutral',
            'angry' => 'Angry',
            default => 'Unknown',
        };
    }
}
