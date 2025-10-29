<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'promotion_usages')
                    ->withPivot('discount_amount')
                    ->withTimestamps();
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('usage_count < usage_limit');
                    });
    }
}
