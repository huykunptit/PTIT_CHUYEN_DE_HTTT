<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class FixBookingExpiresAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:fix-expires-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expires_at for confirmed bookings that still have expiration date';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Fixing expires_at for confirmed bookings...');
        
        // Find all confirmed bookings that still have expires_at
        $bookings = Booking::where('status', 'CONFIRMED')
            ->whereNotNull('expires_at')
            ->get();
        
        $fixedCount = 0;
        
        foreach ($bookings as $booking) {
            $booking->update(['expires_at' => null]);
            $fixedCount++;
            $this->info("Fixed booking: {$booking->booking_code}");
        }
        
        $this->info("Fixed {$fixedCount} bookings.");
        
        return Command::SUCCESS;
    }
}

