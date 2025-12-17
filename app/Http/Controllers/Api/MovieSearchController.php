<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Search",
 *     description="API endpoints for movie search"
 * )
 */
class MovieSearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/search/autocomplete",
     *     summary="Tìm kiếm phim tự động (autocomplete)",
     *     tags={"Search"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Từ khóa tìm kiếm (tối thiểu 2 ký tự)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="genre", type="string"),
     *                     @OA\Property(property="poster_url", type="string"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(property="url", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
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


