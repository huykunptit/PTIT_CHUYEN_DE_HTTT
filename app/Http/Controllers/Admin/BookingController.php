<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema', 'tickets'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }
    
    public function show(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.room.cinema', 'tickets.seat']);
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }
    
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED,EXPIRED',
            'payment_method' => 'nullable|string|max:255',
            'payment_status' => 'nullable|string|max:255',
        ]);
        
        $booking->update($request->all());
        
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Đặt vé đã được cập nhật thành công!');
    }
    
    public function destroy(Booking $booking)
    {
        $booking->delete();
        
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Đặt vé đã được xóa thành công!');
    }
}
