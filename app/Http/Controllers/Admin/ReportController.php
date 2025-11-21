<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    
    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonths(1)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Doanh thu theo ngày
        $dailyRevenue = Booking::where('status', 'CONFIRMED')
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Doanh thu theo rạp
        $revenueByCinema = Cinema::select('cinemas.*', DB::raw('SUM(bookings.final_amount) as revenue'), DB::raw('COUNT(bookings.id) as booking_count'))
            ->join('rooms', 'cinemas.id', '=', 'rooms.cinema_id')
            ->join('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->where('bookings.status', 'CONFIRMED')
            ->whereBetween('bookings.created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupBy('cinemas.id')
            ->orderBy('revenue', 'desc')
            ->get();
        
        // Doanh thu theo phim
        $revenueByMovie = Movie::select('movies.*', DB::raw('SUM(bookings.final_amount) as revenue'), DB::raw('COUNT(bookings.id) as booking_count'))
            ->join('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->where('bookings.status', 'CONFIRMED')
            ->whereBetween('bookings.created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupBy('movies.id')
            ->orderBy('revenue', 'desc')
            ->limit(20)
            ->get();
        
        $totalRevenue = $dailyRevenue->sum('revenue');
        $totalBookings = $dailyRevenue->sum('bookings');
        
        return view('admin.reports.revenue', compact(
            'dailyRevenue',
            'revenueByCinema',
            'revenueByMovie',
            'totalRevenue',
            'totalBookings',
            'startDate',
            'endDate'
        ));
    }
    
    public function bookings(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Bookings theo trạng thái
        $bookingsByStatus = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupBy('status')
            ->get();
        
        // Bookings theo ngày
        $bookingsByDate = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "CONFIRMED" THEN 1 ELSE 0 END) as confirmed'),
                DB::raw('SUM(CASE WHEN status = "PENDING" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "CANCELLED" THEN 1 ELSE 0 END) as cancelled')
            )
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Top phim được đặt nhiều nhất
        $topBookedMovies = Movie::select('movies.*', DB::raw('COUNT(bookings.id) as booking_count'))
            ->join('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->whereBetween('bookings.created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupBy('movies.id')
            ->orderBy('booking_count', 'desc')
            ->limit(10)
            ->get();
        
        $totalBookings = Booking::whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])->count();
        
        return view('admin.reports.bookings', compact(
            'bookingsByStatus',
            'bookingsByDate',
            'topBookedMovies',
            'totalBookings',
            'startDate',
            'endDate'
        ));
    }
}
