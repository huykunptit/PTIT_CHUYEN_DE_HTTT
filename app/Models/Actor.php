<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Actor extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'nationality',
        'biography',
        'image',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_actors')
                    ->withPivot('character_name')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
