<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MovieApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MovieApiController extends Controller
{
    private $movieApiService;
    
    public function __construct(MovieApiService $movieApiService)
    {
        $this->movieApiService = $movieApiService;
    }
    
    /**
     * Lấy danh sách phim đang chiếu
     */
    public function nowPlaying(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $language = $request->get('language', 'vi-VN');
        
        $movies = $this->movieApiService->getNowPlayingMovies($page, $language);
        
        if ($movies) {
            return response()->json([
                'success' => true,
                'data' => $movies,
                'message' => 'Lấy danh sách phim đang chiếu thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy dữ liệu phim'
        ], 500);
    }
    
    /**
     * Lấy danh sách phim sắp chiếu
     */
    public function upcoming(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $language = $request->get('language', 'vi-VN');
        
        $movies = $this->movieApiService->getUpcomingMovies($page, $language);
        
        if ($movies) {
            return response()->json([
                'success' => true,
                'data' => $movies,
                'message' => 'Lấy danh sách phim sắp chiếu thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy dữ liệu phim'
        ], 500);
    }
    
    /**
     * Lấy danh sách phim phổ biến
     */
    public function popular(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $language = $request->get('language', 'vi-VN');
        
        $movies = $this->movieApiService->getPopularMovies($page, $language);
        
        if ($movies) {
            return response()->json([
                'success' => true,
                'data' => $movies,
                'message' => 'Lấy danh sách phim phổ biến thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy dữ liệu phim'
        ], 500);
    }
    
    /**
     * Lấy chi tiết phim
     */
    public function show(Request $request, $movieId): JsonResponse
    {
        $language = $request->get('language', 'vi-VN');
        
        $movie = $this->movieApiService->getMovieDetails($movieId, $language);
        
        if ($movie) {
            return response()->json([
                'success' => true,
                'data' => $movie,
                'message' => 'Lấy chi tiết phim thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy chi tiết phim'
        ], 500);
    }
    
    /**
     * Tìm kiếm phim
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $page = $request->get('page', 1);
        $language = $request->get('language', 'vi-VN');
        
        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm'
            ], 400);
        }
        
        $movies = $this->movieApiService->searchMovies($query, $page, $language);
        
        if ($movies) {
            return response()->json([
                'success' => true,
                'data' => $movies,
                'message' => 'Tìm kiếm phim thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể tìm kiếm phim'
        ], 500);
    }
    
    /**
     * Lấy danh sách thể loại phim
     */
    public function genres(Request $request): JsonResponse
    {
        $language = $request->get('language', 'vi-VN');
        
        $genres = $this->movieApiService->getGenres($language);
        
        if ($genres) {
            return response()->json([
                'success' => true,
                'data' => $genres,
                'message' => 'Lấy danh sách thể loại thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách thể loại'
        ], 500);
    }
    
    /**
     * Lấy danh sách phim theo thể loại
     */
    public function byGenre(Request $request, $genreId): JsonResponse
    {
        $page = $request->get('page', 1);
        $language = $request->get('language', 'vi-VN');
        
        $movies = $this->movieApiService->getMoviesByGenre($genreId, $page, $language);
        
        if ($movies) {
            return response()->json([
                'success' => true,
                'data' => $movies,
                'message' => 'Lấy danh sách phim theo thể loại thành công'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách phim theo thể loại'
        ], 500);
    }
    
    /**
     * Xóa cache
     */
    public function clearCache(): JsonResponse
    {
        $this->movieApiService->clearCache();
        
        return response()->json([
            'success' => true,
            'message' => 'Xóa cache thành công'
        ]);
    }
}
