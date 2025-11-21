<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display staff dashboard
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistics for staff
        $stats = [
            'today_bookings' => Booking::whereDate('created_at', $today)->count(),
            'today_revenue' => Booking::whereDate('created_at', $today)
                ->where('status', 'CONFIRMED')
                ->sum('final_amount'),
            'month_bookings' => Booking::where('created_at', '>=', $thisMonth)->count(),
            'month_revenue' => Booking::where('created_at', '>=', $thisMonth)
                ->where('status', 'CONFIRMED')
                ->sum('final_amount'),
            'pending_bookings' => Booking::where('status', 'PENDING')->count(),
            'confirmed_bookings' => Booking::where('status', 'CONFIRMED')->count(),
        ];

        // Recent bookings
        $recentBookings = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Today's showtimes with bookings
        $todayShowtimes = DB::table('showtimes')
            ->join('bookings', 'showtimes.id', '=', 'bookings.showtime_id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->join('cinemas', 'rooms.cinema_id', '=', 'cinemas.id')
            ->whereDate('showtimes.date', $today)
            ->where('bookings.status', 'CONFIRMED')
            ->select(
                'showtimes.id',
                'showtimes.start_time',
                'movies.title as movie_title',
                'cinemas.name as cinema_name',
                'rooms.name as room_name',
                DB::raw('COUNT(bookings.id) as booking_count')
            )
            ->groupBy('showtimes.id', 'showtimes.start_time', 'movies.title', 'cinemas.name', 'rooms.name')
            ->orderBy('showtimes.start_time')
            ->get();

        return view('staff.dashboard.index', compact('stats', 'recentBookings', 'todayShowtimes'));
    }
}

