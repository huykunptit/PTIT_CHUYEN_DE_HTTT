<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MovieSearchController extends Controller
{
    /**
     * Search movies for autocomplete suggestions
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $movies = Movie::where('title', 'like', '%' . $query . '%')
            ->orWhere('genre', 'like', '%' . $query . '%')
            ->select('id', 'title', 'genre', 'poster', 'status')
            ->limit(10)
            ->get()
            ->map(function($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'genre' => $movie->genre,
                    'poster_url' => $movie->poster_url,
                    'status' => $movie->status,
                    'url' => route('movies.show', $movie)
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $movies
        ]);
    }
}

