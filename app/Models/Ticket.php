<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'booking_id',
        'seat_id',
        'ticket_code',
        'price',
        'status',
        'checked_in_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'checked_in_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'AVAILABLE');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'SOLD');
    }
}
