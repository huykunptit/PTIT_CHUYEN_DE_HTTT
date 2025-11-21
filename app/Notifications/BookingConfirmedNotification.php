<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
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
            ->subject('Xác nhận đặt vé thành công - ' . $this->booking->booking_code)
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Đặt vé của bạn đã được xác nhận thành công.')
            ->line('**Mã đặt vé:** ' . $this->booking->booking_code)
            ->line('**Phim:** ' . $this->booking->showtime->movie->title)
            ->line('**Rạp:** ' . $this->booking->showtime->room->cinema->name)
            ->line('**Phòng:** ' . $this->booking->showtime->room->name)
            ->line('**Ngày chiếu:** ' . $this->booking->showtime->date->format('d/m/Y'))
            ->line('**Giờ chiếu:** ' . \Carbon\Carbon::parse($this->booking->showtime->start_time)->format('H:i'))
            ->line('**Tổng tiền:** ' . number_format($this->booking->final_amount, 0, ',', '.') . '₫')
            ->action('Xem vé', route('tickets.show', $this->booking))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking.confirmed',
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message' => 'Đặt vé ' . $this->booking->booking_code . ' đã được xác nhận thành công!',
            'amount' => $this->booking->final_amount,
            'movie_title' => $this->booking->showtime->movie->title,
        ];
    }
}

