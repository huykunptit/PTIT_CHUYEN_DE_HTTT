<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public string $message;
    protected string $actionLabel = 'Đặt vé';
    protected string $statusLabel = 'Chưa thanh toán';
    protected string $level = 'warning';

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->loadMissing(['user']);
        $this->message = $this->buildMessage();
    }

    public function broadcastOn(): array
    {
        $channels = [];

        if ($this->booking->user_id) {
            $channels[] = new PrivateChannel('notifications.' . $this->booking->user_id);
        }

        $channels[] = new PrivateChannel('admin.notifications');
        $channels[] = new PrivateChannel('staff.notifications');

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'booking.created';
    }

    public function broadcastWith(): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'user_name' => optional($this->booking->user)->name ?? 'Khách hàng',
            'status' => $this->booking->status,
            'status_label' => $this->statusLabel,
            'action_label' => $this->actionLabel,
            'amount' => $this->booking->final_amount,
            'level' => $this->level,
            'message' => $this->message,
            'created_at' => $this->booking->created_at->toIso8601String(),
        ];
    }

    protected function buildMessage(): string
    {
        $name = optional($this->booking->user)->name ?? 'Khách hàng';
        return "{$name} - {$this->actionLabel} - {$this->statusLabel}";
    }
}

