<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatReleased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $showtimeId;
    public $seatId;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $showtimeId, int $seatId, ?int $userId = null)
    {
        $this->showtimeId = $showtimeId;
        $this->seatId = $seatId;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('showtime.' . $this->showtimeId . '.seats'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'seat.released';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'showtime_id' => $this->showtimeId,
            'seat_id' => $this->seatId,
            'user_id' => $this->userId,
            'action' => 'released',
        ];
    }
}

