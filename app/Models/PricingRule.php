<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    protected $fillable = [
        'cinema_id',
        'name',
        'day_type',
        'start_time',
        'end_time',
        'audience_type',
        'seat_type',
        'base_price',
        'surcharge',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'base_price' => 'decimal:2',
        'surcharge' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
