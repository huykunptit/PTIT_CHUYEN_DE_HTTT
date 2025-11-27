<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    /**
     * In PDF cho một vé cụ thể
     */
    public function printPdf(Ticket $ticket)
    {
        $user = auth()->user();
        
        // Kiểm tra quyền truy cập
        if (!$user || ($ticket->booking->user_id !== $user->id && $user->role !== 'admin' && $user->role !== 'staff')) {
            abort(403);
        }

        // Load relationships
        $ticket->load(['booking.user', 'booking.showtime.movie', 'booking.showtime.room.cinema', 'seat']);

        // Generate QR code
        $qrCode = QrCode::format('png')->size(200)->generate($ticket->ticket_code);
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCode);

        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new Dompdf($options);
        
        $html = view('pdf.ticket', [
            'ticket' => $ticket,
            'qrCodeBase64' => $qrCodeBase64,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'ticket-' . $ticket->ticket_code . '.pdf';
        
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * In PDF cho tất cả vé trong một booking
     */
    public function printBookingPdf(Booking $booking)
    {
        $user = auth()->user();
        
        // Kiểm tra quyền truy cập
        if (!$user || ($booking->user_id !== $user->id && $user->role !== 'admin' && $user->role !== 'staff')) {
            abort(403);
        }

        // Load relationships
        $booking->load(['showtime.movie', 'showtime.room.cinema', 'tickets.seat']);
        $tickets = $booking->tickets;

        if ($tickets->isEmpty()) {
            return back()->withErrors(['error' => 'Không có vé nào để in.']);
        }

        // Generate QR codes for all tickets
        $ticketsWithQr = $tickets->map(function ($ticket) {
            $qrCode = QrCode::format('png')->size(200)->generate($ticket->ticket_code);
            $ticket->qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCode);
            return $ticket;
        });

        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new Dompdf($options);
        
        $html = view('pdf.booking-tickets', [
            'booking' => $booking,
            'tickets' => $ticketsWithQr,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'booking-' . $booking->booking_code . '-tickets.pdf';
        
        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

