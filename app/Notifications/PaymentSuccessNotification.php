<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Thanh toán thành công - ' . $this->booking->booking_code)
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Thanh toán cho đặt vé của bạn đã được xử lý thành công.')
            ->line('**Mã đặt vé:** ' . $this->booking->booking_code)
            ->line('**Phương thức thanh toán:** ' . ($this->booking->payment_method ?? 'VNPAY'))
            ->line('**Số tiền:** ' . number_format($this->booking->final_amount, 0, ',', '.') . '₫')
            ->line('**Phim:** ' . $this->booking->showtime->movie->title)
            ->line('**Rạp:** ' . $this->booking->showtime->room->cinema->name)
            ->line('**Ngày chiếu:** ' . $this->booking->showtime->date->format('d/m/Y'))
            ->action('Xem vé', route('tickets.show', $this->booking))
            ->line('Vui lòng lưu mã vé để check-in tại rạp. Cảm ơn bạn!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment.success',
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message' => 'Thanh toán thành công cho đặt vé ' . $this->booking->booking_code,
            'amount' => $this->booking->final_amount,
            'payment_method' => $this->booking->payment_method,
        ];
    }
}

