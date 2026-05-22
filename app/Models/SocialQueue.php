<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialQueue extends Model
{
    protected $fillable = ['name', 'color', 'sort_order'];

    public function entries()
    {
        return $this->hasMany(SocialQueueEntry::class, 'queue_id')->orderBy('sort_order');
    }

    public function pendingEntries()
    {
        return $this->hasMany(SocialQueueEntry::class, 'queue_id')
            ->where('status', 'pending')
            ->orderBy('sort_order');
    }

    public function publishedEntries()
    {
        return $this->hasMany(SocialQueueEntry::class, 'queue_id')
            ->where('status', 'published')
            ->orderByDesc('published_at');
    }
}
