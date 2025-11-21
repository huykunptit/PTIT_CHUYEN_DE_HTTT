<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Hiển thị danh sách vé của booking
     */
    public function show(Booking $booking)
    {
        $user = auth()->user();

        if (!$user || ($booking->user_id !== $user->id && $user->role !== 'admin')) {
            abort(403);
        }

        $booking->load(['showtime.movie', 'showtime.room.cinema']);
        $tickets = $booking->tickets()->with(['seat'])->get();

        return view('frontend.tickets.show', compact('booking', 'tickets'));
    }
    
    /**
     * Hiển thị thông tin chi tiết một vé
     */
    public function detail(Ticket $ticket)
    {
        $ticket->load(['booking.user', 'booking.showtime.movie', 'booking.showtime.room.cinema', 'seat']);

        $user = auth()->user();
        if (!$user || ($ticket->booking->user_id !== $user->id && $user->role !== 'admin')) {
            abort(403);
        }

        return view('frontend.tickets.detail', compact('ticket'));
    }
}

