<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $qrCodeBase64;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        
        // Generate QR code as base64 image for PDF
        $qrCode = QrCode::format('png')->size(200)->generate($ticket->ticket_code);
        $this->qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCode);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vé xem phim - ' . $this->ticket->ticket_code . ' - ' . $this->ticket->booking->showtime->movie->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-pdf',
            with: [
                'ticket' => $this->ticket,
                'qrCodeBase64' => $this->qrCodeBase64,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF using dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        
        $dompdf = new Dompdf($options);
        
        // Load HTML content with QR code
        $html = view('pdf.ticket', [
            'ticket' => $this->ticket,
            'qrCodeBase64' => $this->qrCodeBase64,
        ])->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'ticket-' . $this->ticket->ticket_code . '.pdf';
        
        return [
            Attachment::fromData(fn () => $dompdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}

