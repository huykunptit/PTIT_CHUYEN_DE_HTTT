<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->message = "Đặt vé {$booking->booking_code} đã được xác nhận thành công!";
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        if ($this->booking->user_id) {
            $channels[] = new PrivateChannel('notifications.' . $this->booking->user_id);
        }
        
        // Broadcast to admin channel
        $channels[] = new PrivateChannel('admin.notifications');
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'booking.confirmed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message' => $this->message,
            'status' => $this->booking->status,
            'amount' => $this->booking->final_amount,
            'created_at' => $this->booking->created_at->toIso8601String(),
        ];
    }
}

