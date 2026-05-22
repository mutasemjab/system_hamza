<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialQueueEntry extends Model
{
    protected $fillable = ['queue_id', 'player_id', 'sort_order', 'status', 'published_at'];

    protected $casts = ['published_at' => 'date'];

    public function queue()
    {
        return $this->belongsTo(SocialQueue::class, 'queue_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
