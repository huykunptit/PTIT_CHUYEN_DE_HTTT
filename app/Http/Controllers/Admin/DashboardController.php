<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'total_movies' => Movie::count(),
            'now_showing' => Movie::where('status', 'NOW_SHOWING')->count(),
            'coming_soon' => Movie::where('status', 'COMING_SOON')->count(),
            'total_cinemas' => Cinema::where('is_active', true)->count(),
            'total_rooms' => Room::where('is_active', true)->count(),
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'CONFIRMED')->count(),
            'pending_bookings' => Booking::where('status', 'PENDING')->count(),
            'total_revenue' => Booking::where('status', 'CONFIRMED')->sum('final_amount'),
            'total_tickets' => Ticket::where('status', 'SOLD')->count(),
            'total_users' => User::where('role', 'user')->count(),
        ];

        // Doanh thu theo tháng (12 tháng gần nhất)
        $monthlyRevenue = Booking::where('status', 'CONFIRMED')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 5 phim bán chạy nhất
        $topMovies = Movie::select('movies.*', DB::raw('COUNT(bookings.id) as booking_count'), DB::raw('SUM(bookings.final_amount) as revenue'))
            ->join('showtimes', 'movies.id', '=', 'showtimes.movie_id')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->where('bookings.status', 'CONFIRMED')
            ->groupBy('movies.id')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        // Top 5 rạp chiếu doanh thu cao nhất
        $topCinemas = Cinema::select('cinemas.*', DB::raw('SUM(bookings.final_amount) as revenue'), DB::raw('COUNT(bookings.id) as booking_count'))
            ->join('rooms', 'cinemas.id', '=', 'rooms.cinema_id')
            ->join('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->where('bookings.status', 'CONFIRMED')
            ->groupBy('cinemas.id')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();

        // Booking theo ngày (30 ngày gần nhất)
        $dailyBookings = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "CONFIRMED" THEN final_amount ELSE 0 END) as revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Trạng thái booking
        $bookingStatus = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Lịch chiếu hôm nay
        $todayShowtimes = Showtime::with(['movie', 'room.cinema'])
            ->whereDate('date', Carbon::today())
            ->where('status', 'ACTIVE')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'monthlyRevenue',
            'topMovies',
            'topCinemas',
            'dailyBookings',
            'bookingStatus',
            'todayShowtimes'
        ));
    }
}
