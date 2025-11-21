<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Cinema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('genre')) {
            $query->where('genre', 'like', '%' . $request->genre . '%');
        }
        
        $movies = $query->select('id', 'title', 'description', 'genre', 'rating', 'release_date', 'status')
            ->paginate(12);
        
        // Cache cinemas list for 1 hour
        $cinemas = Cache::remember('active_cinemas', 3600, function () {
            return Cinema::where('is_active', true)
                ->select('id', 'name', 'address', 'city')
                ->orderBy('city')
                ->orderBy('name')
                ->get();
        });
        
        return view('frontend.movies.index', compact('movies', 'cinemas'));
    }
    
    public function show(Request $request, Movie $movie)
    {
        $cacheKey = "movie_{$movie->id}_showtimes_" . md5($request->getQueryString());
        $hasFilters = $request->filled('cinema_id') || $request->filled('date') || $request->filled('city');

        $buildQuery = function () use ($movie) {
            return Showtime::where('movie_id', $movie->id)
                ->where('date', '>=', now()->toDateString())
                ->with(['room' => function($q) {
                    $q->select('id', 'name', 'cinema_id');
                }, 'room.cinema' => function($q) {
                    $q->select('id', 'name', 'address', 'city');
                }])
                ->select('id', 'movie_id', 'room_id', 'date', 'start_time', 'end_time', 'status');
        };

        if ($hasFilters) {
            $query = $buildQuery();

            if ($request->filled('cinema_id')) {
                $query->whereHas('room', function($q) use ($request) {
                    $q->where('cinema_id', $request->cinema_id);
                });
            }

            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }

            if ($request->filled('city')) {
                $query->whereHas('room.cinema', function($q) use ($request) {
                    $q->where('city', $request->city);
                });
            }

            $showtimes = $query->get()->groupBy(function($showtime) {
                return $showtime->date->format('Y-m-d');
            });
        } else {
            $showtimes = Cache::remember($cacheKey, 1800, function () use ($buildQuery) {
                return $buildQuery()->get()->groupBy(function($showtime) {
                    return $showtime->date->format('Y-m-d');
                });
            });
        }
        
        // Cache cinemas list
        $cinemas = Cache::remember('active_cinemas', 3600, function () {
            return Cinema::where('is_active', true)
                ->select('id', 'name', 'address', 'city')
                ->orderBy('city')
                ->orderBy('name')
                ->get();
        });
        
        $selectedCinemaId = $request->cinema_id;
        $selectedDate = $request->date;
        $selectedCity = $request->city;
        
        return view('frontend.movies.show', compact('movie', 'showtimes', 'cinemas', 'selectedCinemaId', 'selectedDate', 'selectedCity'));
    }
}
