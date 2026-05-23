<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id', 'custom_description', 'status', 'published_at', 'notes',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'published' => 'تم النشر',
            'pending'   => 'قادم',
            default     => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'published' => 'badge-success',
            'pending'   => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }
}
