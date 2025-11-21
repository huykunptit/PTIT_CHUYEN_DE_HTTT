<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTicketsController extends Controller
{
    /**
     * Hiển thị danh sách vé của user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem vé của bạn.');
        }

        $query = $user->bookings()
            ->with(['showtime.movie', 'showtime.room.cinema', 'tickets'])
            ->orderBy('created_at', 'desc');

        // Filter theo trạng thái
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter theo phim
        if ($request->has('movie_id')) {
            $query->whereHas('showtime', function($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        $bookings = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => $user->bookings()->count(),
            'confirmed' => $user->bookings()->where('status', 'CONFIRMED')->count(),
            'pending' => $user->bookings()->where('status', 'PENDING')->count(),
            'cancelled' => $user->bookings()->where('status', 'CANCELLED')->count(),
        ];

        return view('frontend.my-tickets.index', compact('bookings', 'stats'));
    }

    /**
     * Hủy booking
     */
    public function cancel(Booking $booking)
    {
        $user = Auth::user();

        // Kiểm tra quyền
        if ($booking->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Bạn không có quyền hủy booking này.');
        }

        // Chỉ cho phép hủy nếu chưa thanh toán
        if ($booking->status !== 'PENDING') {
            return redirect()->back()->with('error', 'Chỉ có thể hủy booking chưa thanh toán.');
        }

        // Hủy booking và giải phóng vé
        $booking->update(['status' => 'CANCELLED']);
        $booking->tickets()->update(['status' => 'AVAILABLE']);

        return redirect()->back()->with('success', 'Đã hủy booking thành công!');
    }
}

