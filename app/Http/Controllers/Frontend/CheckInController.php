<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    /**
     * Hiển thị form check-in
     */
    public function show()
    {
        return view('frontend.checkin.show');
    }
    
    /**
     * Xử lý check-in với ticket code
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
            return back()->withErrors(['ticket_code' => 'Vé đã được sử dụng!'])->withInput();
        }
        
        // Kiểm tra thời gian check-in (chỉ cho phép check-in trong ngày chiếu hoặc trước 30 phút)
        $showtime = $ticket->booking->showtime;
        $showDate = $showtime->date;
        $startTime = is_string($showtime->start_time) ? $showtime->start_time : $showtime->start_time->format('H:i:s');
        $showDateTime = $showDate->format('Y-m-d') . ' ' . $startTime;
        $showStartTime = \Carbon\Carbon::parse($showDateTime);
        $checkInStartTime = $showStartTime->copy()->subMinutes(30);
        $checkInEndTime = $showStartTime->copy()->addHours(3); // Cho phép check-in sau giờ chiếu 3 tiếng
        
        if (now()->lt($checkInStartTime)) {
            return back()->withErrors(['ticket_code' => 'Chưa đến thời gian check-in! Vui lòng quay lại 30 phút trước giờ chiếu.'])->withInput();
        }
        
        if (now()->gt($checkInEndTime)) {
            return back()->withErrors(['ticket_code' => 'Đã quá thời gian check-in!'])->withInput();
        }
        
        // Thực hiện check-in
        $ticket->update([
            'status' => 'USED',
            'checked_in_at' => now(),
        ]);
        
        return view('frontend.checkin.success', compact('ticket'));
    }
    
    /**
     * Hiển thị thông tin vé sau khi check-in
     */
    public function ticketInfo($ticketCode)
    {
        $ticket = Ticket::where('ticket_code', strtoupper($ticketCode))
            ->with(['booking.showtime.movie', 'booking.showtime.room.cinema', 'seat'])
            ->firstOrFail();
        
        return view('frontend.checkin.ticket-info', compact('ticket'));
    }
}

