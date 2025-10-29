<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'showtime_id',
        'booking_code',
        'total_amount',
        'discount_amount',
        'final_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_details',
        'expires_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'payment_details' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_usages')
                    ->withPivot('discount_amount')
                    ->withTimestamps();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'CONFIRMED');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
