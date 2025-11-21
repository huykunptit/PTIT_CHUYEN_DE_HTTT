<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings (staff can view all)
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.room.cinema', 'tickets']);
        
        return view('staff.bookings.show', compact('booking'));
    }

    /**
     * Update booking status (staff can update status)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED,COMPLETED',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        // If cancelled, release tickets
        if ($request->status === 'CANCELLED') {
            $booking->tickets()->update(['status' => 'AVAILABLE']);
        }

        // If confirmed, mark tickets as sold
        if ($request->status === 'CONFIRMED') {
            $booking->tickets()->update(['status' => 'SOLD']);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}

