<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id', 'total_amount', 'paid_amount',
        'last_payment_date', 'start_date', 'end_date', 'status', 'notes',
    ];

    protected $casts = [
        'total_amount'      => 'decimal:2',
        'paid_amount'       => 'decimal:2',
        'last_payment_date' => 'date',
        'start_date'        => 'date',
        'end_date'          => 'date',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->paid_amount);
    }

    public function getPaymentPercentAttribute(): int
    {
        if ($this->total_amount <= 0) return 100;
        return (int) min(100, round(($this->paid_amount / $this->total_amount) * 100));
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->end_date && $this->end_date->diffInDays(now()) <= 7 && $this->end_date->isFuture();
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'active'  => ['label' => 'فعال',   'class' => 'badge-success'],
            'late'    => ['label' => 'متأخر',  'class' => 'badge-warning'],
            'expired' => ['label' => 'منتهي',  'class' => 'badge-danger'],
            default   => ['label' => $this->status, 'class' => 'badge-secondary'],
        };
    }
}
