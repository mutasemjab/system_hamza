<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'birth_date', 'phone', 'weight', 'height', 'photo', 'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight'     => 'decimal:2',
        'height'     => 'decimal:2',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function socialContents()
    {
        return $this->hasMany(SocialContent::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? url('assets/admin/uploads/' . $this->photo) : null;
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->full_name));
        $initials = strtoupper(substr($words[0], 0, 1));
        if (isset($words[1])) {
            $initials .= strtoupper(substr($words[1], 0, 1));
        }
        return $initials;
    }
}
