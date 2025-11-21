<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache data for 15 minutes to improve performance
        $cacheKey = 'home_page_data';
        
        $data = Cache::remember($cacheKey, 900, function () {
            // Featured movies for hero section
            $featuredMovie = Movie::featured()->nowShowing()->first() 
                ?? Movie::nowShowing()->orderBy('rating', 'desc')->first();
            
            // Phim đang chiếu - chỉ lấy các trường cần thiết
            $nowShowingMovies = Movie::where('status', 'NOW_SHOWING')
                ->select('id', 'title', 'description', 'genre', 'rating', 'release_date', 'status')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
            
            // Phim sắp chiếu
            $comingSoonMovies = Movie::where('status', 'COMING_SOON')
                ->select('id', 'title', 'description', 'genre', 'rating', 'release_date', 'status')
                ->orderBy('release_date', 'asc')
                ->take(6)
                ->get();
            
            // Vouchers/Promotions đang active
            $promotions = Promotion::available()
                ->select('id', 'name', 'code', 'description', 'type', 'value', 'min_amount', 'start_date', 'end_date')
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            
            return compact(
                'featuredMovie',
                'nowShowingMovies',
                'comingSoonMovies',
                'promotions'
            );
        });
        
        return view('frontend.home', $data);
    }
    
}
