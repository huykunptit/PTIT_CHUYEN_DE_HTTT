<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Clear cache when models are updated
        Event::listen([
            'eloquent.created: App\Models\Movie',
            'eloquent.updated: App\Models\Movie',
            'eloquent.deleted: App\Models\Movie',
        ], function () {
            Cache::forget('home_page_data');
        });

        Event::listen([
            'eloquent.created: App\Models\Promotion',
            'eloquent.updated: App\Models\Promotion',
            'eloquent.deleted: App\Models\Promotion',
        ], function () {
            Cache::forget('home_page_data');
        });

        Event::listen([
            'eloquent.created: App\Models\Cinema',
            'eloquent.updated: App\Models\Cinema',
            'eloquent.deleted: App\Models\Cinema',
        ], function () {
            Cache::forget('active_cinemas');
        });

        Event::listen([
            'eloquent.created: App\Models\Showtime',
            'eloquent.updated: App\Models\Showtime',
            'eloquent.deleted: App\Models\Showtime',
        ], function ($showtime) {
            if (isset($showtime->movie_id)) {
                // Clear all showtime caches for this movie
                // Note: Redis supports pattern matching, file cache doesn't
                // For production, use Redis cache driver
                if (config('cache.default') === 'redis') {
                    $keys = Cache::getRedis()->keys("movie_{$showtime->movie_id}_showtimes_*");
                    if ($keys) {
                        Cache::getRedis()->del($keys);
                    }
                }
            }
            Cache::forget("showtime_{$showtime->id}_seats");
        });
    }
}

