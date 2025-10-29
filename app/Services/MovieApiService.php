<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MovieApiService
{
    private $apiKey;
    private $baseUrl;
    private $timeout;
    
    public function __construct()
    {
        $this->apiKey = env('TMDB_API_KEY', 'your-api-key-here');
        $this->baseUrl = env('TMDB_BASE_URL', 'https://api.themoviedb.org/3');
        $this->timeout = 10;
    }
    
    /**
     * Lấy danh sách phim đang chiếu
     */
    public function getNowPlayingMovies($page = 1, $language = 'vi-VN')
    {
        return Cache::remember("now_playing_movies_{$page}", 1800, function () use ($page, $language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/movie/now_playing", [
                    'api_key' => $this->apiKey,
                    'language' => $language,
                    'page' => $page
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Now Playing): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy danh sách phim sắp chiếu
     */
    public function getUpcomingMovies($page = 1, $language = 'vi-VN')
    {
        return Cache::remember("upcoming_movies_{$page}", 1800, function () use ($page, $language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/movie/upcoming", [
                    'api_key' => $this->apiKey,
                    'language' => $language,
                    'page' => $page
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Upcoming): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy danh sách phim phổ biến
     */
    public function getPopularMovies($page = 1, $language = 'vi-VN')
    {
        return Cache::remember("popular_movies_{$page}", 1800, function () use ($page, $language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/movie/popular", [
                    'api_key' => $this->apiKey,
                    'language' => $language,
                    'page' => $page
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Popular): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy chi tiết phim
     */
    public function getMovieDetails($movieId, $language = 'vi-VN')
    {
        return Cache::remember("movie_details_{$movieId}", 3600, function () use ($movieId, $language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/movie/{$movieId}", [
                    'api_key' => $this->apiKey,
                    'language' => $language,
                    'append_to_response' => 'credits,videos,images'
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Movie Details): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Tìm kiếm phim
     */
    public function searchMovies($query, $page = 1, $language = 'vi-VN')
    {
        return Cache::remember("search_movies_{$query}_{$page}", 600, function () use ($query, $page, $language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/search/movie", [
                    'api_key' => $this->apiKey,
                    'language' => $language,
                    'query' => $query,
                    'page' => $page
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Search): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy danh sách phim theo thể loại
     */
    public function getMoviesByGenre($genreId, $page = 1, $language = 'vi-VN')
    {
        return Cache::remember("movies_by_genre_{$genreId}_{$page}", 1800, function () use ($genreId, $page, $language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/discover/movie", [
                    'api_key' => $this->apiKey,
                    'language' => $language,
                    'with_genres' => $genreId,
                    'page' => $page
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Genre): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy danh sách thể loại phim
     */
    public function getGenres($language = 'vi-VN')
    {
        return Cache::remember("movie_genres_{$language}", 86400, function () use ($language) {
            try {
                $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/genre/movie/list", [
                    'api_key' => $this->apiKey,
                    'language' => $language
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                Log::error('TMDB API Error (Genres): ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Xóa cache
     */
    public function clearCache()
    {
        Cache::forget('now_playing_movies_1');
        Cache::forget('upcoming_movies_1');
        Cache::forget('popular_movies_1');
        Cache::forget('movie_genres_vi-VN');
        
        return true;
    }
}
