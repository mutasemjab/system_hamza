<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id', 'content_type', 'custom_description', 'scheduled_date',
        'status', 'published_at', 'sort_order', 'notes',
    ];

    protected $casts = [
        'published_at'   => 'date',
        'scheduled_date' => 'date',
        'sort_order'     => 'integer',
    ];

    public static array $types = [
        'story'             => ['label' => 'الستوري',        'icon' => 'fab fa-instagram', 'color' => '#e1306c'],
        'player_feature'    => ['label' => 'معاملة اللاعب',  'icon' => 'fas fa-star',      'color' => '#6366f1'],
        'anime_version'     => ['label' => 'Anime Version',  'icon' => 'fas fa-dragon',    'color' => '#8b5cf6'],
        'exercise_champion' => ['label' => 'بطل التمرين',    'icon' => 'fas fa-trophy',    'color' => '#f59e0b'],
        'carousel'          => ['label' => 'Carousel',       'icon' => 'fas fa-images',    'color' => '#10b981'],
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /* ── Accessors ── */

    public function getTypeLabelAttribute(): string
    {
        if ($this->custom_description) {
            return $this->custom_description;
        }
        return self::$types[$this->content_type]['label'] ?? ($this->content_type ?? '—');
    }

    public function getTypeIconAttribute(): string
    {
        return self::$types[$this->content_type]['icon'] ?? 'fas fa-calendar-alt';
    }

    public function getTypeColorAttribute(): string
    {
        return self::$types[$this->content_type]['color'] ?? '#6366f1';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'next'      => 'دوره الآن',
            'pending'   => 'في الانتظار',
            'published' => 'تم النشر',
            default     => $this->status,
        };
    }

    /* ── Scopes ── */

    public function scopeScheduled($query)
    {
        return $query->whereNull('content_type')->whereNotNull('scheduled_date');
    }

    public function scopeTyped($query)
    {
        return $query->whereNotNull('content_type');
    }
}
