<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MovieApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="Cinema Booking System API",
 *     version="1.0.0",
 *     description="API documentation for Cinema Booking System",
 *     @OA\Contact(
 *         email="support@cinemat.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="{protocol}://{host}",
 *     description="API Server",
 *     @OA\ServerVariable(
 *         serverVariable="protocol",
 *         enum={"http", "https"},
 *         default="http"
 *     ),
 *     @OA\ServerVariable(
 *         serverVariable="host",
 *         default="localhost:8089"
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class MovieApiController extends Controller
{
    private $movieApiService;
    
    public function __construct(MovieApiService $movieApiService)
    {
        $this->movieApiService = $movieApiService;
    }
    
    /**
     * @OA\Get(
     *     path="/api/movies/now-playing",
     *     summary="Lấy danh sách phim đang chiếu",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Số trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         description="Ngôn ngữ",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Lấy danh sách phim đang chiếu thành công")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Get(
     *     path="/api/movies/upcoming",
     *     summary="Lấy danh sách phim sắp chiếu",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Get(
     *     path="/api/movies/popular",
     *     summary="Lấy danh sách phim phổ biến",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Get(
     *     path="/api/movies/{movieId}",
     *     summary="Lấy chi tiết phim",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="movieId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Get(
     *     path="/api/movies/search",
     *     summary="Tìm kiếm phim",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=400, description="Thiếu từ khóa tìm kiếm"),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Get(
     *     path="/api/movies/genres",
     *     summary="Lấy danh sách thể loại phim",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Get(
     *     path="/api/movies/genre/{genreId}",
     *     summary="Lấy danh sách phim theo thể loại",
     *     tags={"Movies"},
     *     @OA\Parameter(
     *         name="genreId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="language",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", default="vi-VN")
     *     ),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=500, description="Lỗi server")
     * )
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
     * @OA\Post(
     *     path="/api/movies/clear-cache",
     *     summary="Xóa cache phim",
     *     tags={"Movies"},
     *     @OA\Response(response=200, description="Thành công")
     * )
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
