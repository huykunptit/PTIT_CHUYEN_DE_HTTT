<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PhoneOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly int $otp)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mã đăng nhập OTP - Cinema')
            ->greeting('Xin chào ' . ($notifiable->name ?? ''))
            ->line('Mã xác thực đăng nhập của bạn là:')
            ->line('# ' . $this->otp . ' #')
            ->line('Mã có hiệu lực trong ' . (int) env('OTP_EXPIRATION_SECONDS', 300) / 60 . ' phút.')
            ->line('Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email.');
    }
}

