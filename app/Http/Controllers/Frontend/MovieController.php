<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;

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
        
        $movies = $query->paginate(12);
        
        return view('frontend.movies.index', compact('movies'));
    }
    
    public function show(Movie $movie)
    {
        $showtimes = Showtime::where('movie_id', $movie->id)
            ->where('date', '>=', now()->toDateString())
            ->with(['room.cinema'])
            ->get()
            ->groupBy('date');
            
        return view('frontend.movies.show', compact('movie', 'showtimes'));
    }
}
