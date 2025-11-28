<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'poster',
        'trailer_url',
        'duration',
        'genre',
        'country',
        'language',
        'age_rating',
        'release_date',
        'end_date',
        'status',
        'rating',
        'cast',
        'is_featured',
    ];

    protected $casts = [
        'release_date' => 'date',
        'end_date' => 'date',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'cast' => 'array',
    ];

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'movie_actors')
                    ->withPivot('character_name')
                    ->withTimestamps();
    }

    public function directors(): BelongsToMany
    {
        return $this->belongsToMany(Director::class, 'movie_directors')
                    ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasManyThrough(Booking::class, Showtime::class);
    }

    public function scopeNowShowing($query)
    {
        return $query->where('status', 'NOW_SHOWING');
    }

    public function scopeComingSoon($query)
    {
        return $query->where('status', 'COMING_SOON');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the poster URL attribute
     */
    public function getPosterUrlAttribute()
    {
        if (!$this->poster) {
            return null;
        }

        // Nếu là full URL (http/https) thì trả về trực tiếp
        if (str_starts_with($this->poster, 'http://') || str_starts_with($this->poster, 'https://')) {
            return $this->poster;
        }

        // Nếu là relative path thì trả về asset URL
        return asset($this->poster);
    }
}
