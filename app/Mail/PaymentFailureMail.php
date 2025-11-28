<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailureMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $messageText;
    public array $paymentDetails;

    public function __construct(Booking $booking, string $messageText, array $paymentDetails = [])
    {
        $this->booking = $booking;
        $this->messageText = $messageText;
        $this->paymentDetails = $paymentDetails;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanh toán không thành công - ' . $this->booking->booking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-failure',
            with: [
                'booking' => $this->booking,
                'messageText' => $this->messageText,
                'paymentDetails' => $this->paymentDetails,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

