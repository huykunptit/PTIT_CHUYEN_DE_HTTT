<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    /**
     * Hiển thị form check-in cho staff
     */
    public function show()
    {
        return view('staff.checkin.show');
    }
    
    /**
     * Xử lý check-in với ticket code (staff có thể check-in bất kỳ lúc nào)
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string|size:10',
        ]);
        
        $ticketCode = strtoupper($request->ticket_code);
        
        // Tìm vé theo mã
        $ticket = Ticket::where('ticket_code', $ticketCode)
            ->with(['booking.showtime.movie', 'booking.showtime.room.cinema', 'seat'])
            ->first();
        
        if (!$ticket) {
            return back()->withErrors(['ticket_code' => 'Mã vé không tồn tại!'])->withInput();
        }
        
        // Kiểm tra trạng thái booking
        if ($ticket->booking->status !== 'CONFIRMED') {
            return back()->withErrors(['ticket_code' => 'Vé chưa được xác nhận thanh toán!'])->withInput();
        }
        
        // Kiểm tra vé đã được check-in chưa
        if ($ticket->status === 'USED') {
            return back()->with('warning', 'Vé đã được sử dụng!')->withInput();
        }
        
        // Staff có thể check-in bất kỳ lúc nào (không cần kiểm tra thời gian)
        
        // Thực hiện check-in
        $ticket->update([
            'status' => 'USED',
            'checked_in_at' => now(),
        ]);
        
        return view('staff.checkin.success', compact('ticket'));
    }
    
    /**
     * Hiển thị thông tin vé
     */
    public function ticketInfo($ticketCode)
    {
        $ticket = Ticket::where('ticket_code', strtoupper($ticketCode))
            ->with(['booking.showtime.movie', 'booking.showtime.room.cinema', 'seat'])
            ->firstOrFail();
        
        return view('staff.checkin.ticket-info', compact('ticket'));
    }
}

