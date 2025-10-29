<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Banner;
use App\Models\Cinema;
use App\Services\MovieApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Khởi tạo MovieApiService
        $movieApiService = new MovieApiService();
        
        // Lấy dữ liệu từ API
        $apiMovies = $movieApiService->getNowPlayingMovies();
        $upcomingMovies = $movieApiService->getUpcomingMovies();
        $popularMovies = $movieApiService->getPopularMovies();
        
        // Featured movies for hero section
        $featuredMovies = Movie::featured()->nowShowing()->take(6)->get();
        
        // Coming soon movies
        $comingSoonMovies = Movie::comingSoon()->take(4)->get();
        
        // Banners for carousel
        $banners = Banner::active()->ordered()->get();
        
        // Latest movies by country/genre
        $koreanMovies = Movie::where('country', 'like', '%Korea%')
            ->orWhere('country', 'like', '%Hàn%')
            ->latest()
            ->take(10)
            ->get();
            
        $chineseMovies = Movie::where('country', 'like', '%China%')
            ->orWhere('country', 'like', '%Trung%')
            ->latest()
            ->take(10)
            ->get();
            
        $usUkMovies = Movie::whereIn('country', ['USA', 'UK', 'United States', 'United Kingdom'])
            ->latest()
            ->take(10)
            ->get();
        
        // Categories for the grid - focused on booking experience
        $categories = [
            [
                'name' => 'IMAX',
                'color' => 'linear-gradient(135deg, #3b82f6, #1d4ed8)',
                'link' => '#'
            ],
            [
                'name' => '4DX',
                'color' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
                'link' => '#'
            ],
            [
                'name' => 'VIP',
                'color' => 'linear-gradient(135deg, #10b981, #059669)',
                'link' => '#'
            ],
            [
                'name' => '3D',
                'color' => 'linear-gradient(135deg, #f59e0b, #d97706)',
                'link' => '#'
            ],
            [
                'name' => 'Combo',
                'color' => 'linear-gradient(135deg, #ef4444, #dc2626)',
                'link' => '#'
            ],
            [
                'name' => 'Khuyến mãi',
                'color' => 'linear-gradient(135deg, #f97316, #ea580c)',
                'link' => '#'
            ],
            [
                'name' => 'Rạp gần bạn',
                'color' => 'linear-gradient(135deg, #6b7280, #4b5563)',
                'link' => '#'
            ]
        ];
        
        return view('frontend.home', compact(
            'featuredMovies', 
            'comingSoonMovies', 
            'banners',
            'koreanMovies',
            'chineseMovies', 
            'usUkMovies',
            'categories',
            'apiMovies',
            'upcomingMovies',
            'popularMovies'
        ));
    }
    
    /**
     * Lấy dữ liệu phim từ API
     */
    private function getMoviesFromAPI()
    {
        // Cache API response trong 30 phút để tránh gọi API quá nhiều
        return Cache::remember('api_movies', 1800, function () {
            try {
                // Ví dụ API TMDB (The Movie Database)
                $response = Http::timeout(10)->get('https://api.themoviedb.org/3/movie/now_playing', [
                    'api_key' => env('TMDB_API_KEY', 'your-api-key-here'),
                    'language' => 'vi-VN',
                    'page' => 1
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                // Log lỗi nếu cần
                \Log::error('API Movies Error: ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy dữ liệu phim từ API khác (ví dụ: OMDb API)
     */
    private function getMoviesFromOMDbAPI()
    {
        return Cache::remember('omdb_movies', 3600, function () {
            try {
                $response = Http::timeout(10)->get('http://www.omdbapi.com/', [
                    'apikey' => env('OMDB_API_KEY', 'your-api-key-here'),
                    's' => 'movie',
                    'type' => 'movie',
                    'page' => 1
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                \Log::error('OMDb API Error: ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * Lấy dữ liệu phim từ API tùy chỉnh
     */
    private function getMoviesFromCustomAPI()
    {
        return Cache::remember('custom_api_movies', 1800, function () {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . env('CUSTOM_API_TOKEN'),
                        'Accept' => 'application/json'
                    ])
                    ->get(env('CUSTOM_API_URL') . '/movies');
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            } catch (\Exception $e) {
                \Log::error('Custom API Error: ' . $e->getMessage());
                return null;
            }
        });
    }
}
