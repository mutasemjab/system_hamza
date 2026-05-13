<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id', 'total_amount', 'paid_amount',
        'last_payment_date', 'start_date', 'end_date',
        'status', 'notes',
        'is_frozen', 'frozen_at', 'frozen_days_remaining', 'freeze_note',
    ];

    protected $casts = [
        'total_amount'          => 'decimal:2',
        'paid_amount'           => 'decimal:2',
        'last_payment_date'     => 'date',
        'start_date'            => 'date',
        'end_date'              => 'date',
        'frozen_at'             => 'date',
        'is_frozen'             => 'boolean',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /* ── Computed attributes ── */

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
        return $this->end_date
            && !$this->is_frozen
            && $this->end_date->diffInDays(now()) <= 7
            && $this->end_date->isFuture();
    }

    public function getStatusBadgeAttribute(): array
    {
        if ($this->is_frozen) {
            return ['label' => 'مجمّد', 'class' => 'badge-info'];
        }
        return match ($this->status) {
            'active'  => ['label' => 'فعال',   'class' => 'badge-success'],
            'late'    => ['label' => 'متأخر',  'class' => 'badge-warning'],
            'expired' => ['label' => 'منتهي',  'class' => 'badge-danger'],
            default   => ['label' => $this->status, 'class' => 'badge-secondary'],
        };
    }

    /* ── Freeze / Unfreeze helpers ── */

    public function freeze(Carbon $date, ?string $note = null): void
    {
        $remaining = ($this->end_date && $date->lt($this->end_date))
            ? (int) $date->diffInDays($this->end_date)
            : 0;

        $this->update([
            'is_frozen'              => true,
            'frozen_at'              => $date->toDateString(),
            'frozen_days_remaining'  => $remaining,
            'freeze_note'            => $note,
        ]);
    }

    public function unfreeze(): void
    {
        $newEndDate = $this->end_date;

        if ($this->frozen_days_remaining > 0) {
            $newEndDate = Carbon::today()->addDays($this->frozen_days_remaining);
        }

        $this->update([
            'is_frozen'             => false,
            'frozen_at'             => null,
            'frozen_days_remaining' => null,
            'freeze_note'           => null,
            'end_date'              => $newEndDate,
            'status'                => 'active',
        ]);
    }
}
