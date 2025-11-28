<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public array $paymentDetails;

    public function __construct(Booking $booking, array $paymentDetails = [])
    {
        $this->booking = $booking;
        $this->paymentDetails = $paymentDetails;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Biên lai thanh toán - ' . $this->booking->booking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-success',
            with: [
                'booking' => $this->booking,
                'paymentDetails' => $this->paymentDetails,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

