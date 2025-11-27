<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Ticket;
use App\Services\SeatHoldService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    protected $seatHoldService;

    public function __construct(SeatHoldService $seatHoldService)
    {
        $this->seatHoldService = $seatHoldService;
    }

    public function create(Showtime $showtime)
    {
        // Load relationships efficiently
        $showtime->load(['movie:id,title,genre', 'room:id,name,cinema_id', 'room.cinema:id,name']);
        
        // Cache seats data for 5 minutes
        $cacheKey = "showtime_{$showtime->id}_seats";
        $seats = \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($showtime) {
            return Seat::where('room_id', $showtime->room_id)
                ->select('id', 'room_id', 'row', 'number', 'price', 'type')
            ->with(['tickets' => function($query) use ($showtime) {
                    $query->select('id', 'seat_id', 'booking_id')
                        ->whereHas('booking', function($q) use ($showtime) {
                    $q->where('showtime_id', $showtime->id)
                      ->whereIn('status', ['CONFIRMED', 'PENDING']);
                });
            }])
            ->get();
        });

        // Get held seats from Redis
        $heldSeats = $this->seatHoldService->getHeldSeats($showtime->id);
            
        return view('frontend.booking.create', compact('showtime', 'seats', 'heldSeats'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);
        
        $showtime = Showtime::findOrFail($request->showtime_id);
        $seats = Seat::whereIn('id', $request->seat_ids)->get();
        
        // Validate seats are not already held by someone else
        foreach ($request->seat_ids as $seatId) {
            if ($this->seatHoldService->isSeatHeld($showtime->id, $seatId)) {
                $holdInfo = $this->seatHoldService->getSeatHold($showtime->id, $seatId);
                // Check if held by current user
                if ($holdInfo && $holdInfo['user_id'] != auth()->id()) {
                    return back()->withErrors(['seat_ids' => 'Một số ghế đã được người khác giữ chỗ. Vui lòng chọn ghế khác.'])->withInput();
                }
            }
        }
        
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
            'expires_at' => now()->addMinutes(5), // 5 phút theo yêu cầu
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

        // Release held seats (they are now booked)
        $this->seatHoldService->releaseSeats($showtime->id, $request->seat_ids);
        
        return redirect()->route('payment.index', $booking);
    }
}
