<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\SeatHoldService;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending bookings that have passed their expiration time';

    protected $seatHoldService;

    /**
     * Create a new command instance.
     */
    public function __construct(SeatHoldService $seatHoldService)
    {
        parent::__construct();
        $this->seatHoldService = $seatHoldService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Expiring pending bookings...');
        
        // Find all pending bookings that have expired
        $expiredBookings = Booking::where('status', 'PENDING')
            ->where('expires_at', '<', now())
            ->with(['tickets.seat', 'showtime'])
            ->get();
        
        $expiredCount = 0;
        
        foreach ($expiredBookings as $booking) {
            // Update booking status
            $booking->update(['status' => 'EXPIRED']);
            
            // Release seats from Redis holds
            $seatIds = $booking->tickets->pluck('seat_id')->toArray();
            $this->seatHoldService->releaseSeats($booking->showtime_id, $seatIds);
            
            // Update ticket status
            $booking->tickets()->update(['status' => 'AVAILABLE']);
            
            $expiredCount++;
            
            $this->info("Expired booking: {$booking->booking_code}");
        }
        
        $this->info("Expired {$expiredCount} bookings.");
        
        return Command::SUCCESS;
    }
}

