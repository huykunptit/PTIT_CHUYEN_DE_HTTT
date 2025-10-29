<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Showtime $showtime)
    {
        $seats = Seat::where('room_id', $showtime->room_id)
            ->with(['tickets' => function($query) use ($showtime) {
                $query->whereHas('booking', function($q) use ($showtime) {
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('status', ['CONFIRMED', 'PENDING']);
                });
            }])
            ->get();
            
        return view('frontend.booking.create', compact('showtime', 'seats'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email',
        ]);
        
        $showtime = Showtime::findOrFail($request->showtime_id);
        $seats = Seat::whereIn('id', $request->seat_ids)->get();
        
        // Calculate total amount
        $totalAmount = $seats->sum('price');
        
        // Create booking
        $booking = Booking::create([
            'user_id' => auth()->id() ?? null,
            'showtime_id' => $showtime->id,
            'booking_code' => 'BK' . strtoupper(Str::random(8)),
            'total_amount' => $totalAmount,
            'final_amount' => $totalAmount,
            'status' => 'PENDING',
            'expires_at' => now()->addMinutes(15),
        ]);
        
        // Create tickets
        foreach ($seats as $seat) {
            Ticket::create([
                'booking_id' => $booking->id,
                'seat_id' => $seat->id,
                'ticket_code' => 'TK' . strtoupper(Str::random(8)),
                'price' => $seat->price,
                'status' => 'BOOKED',
            ]);
        }
        
        return redirect()->route('payment.index', $booking);
    }
}
