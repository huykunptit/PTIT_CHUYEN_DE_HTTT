<?php

namespace App\Console\Commands;

use App\Services\SeatHoldService;
use Illuminate\Console\Command;

class CleanupExpiredSeatHolds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:cleanup-expired-holds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired seat holds from Redis';

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
        $this->info('Cleaning up expired seat holds...');
        
        $cleaned = $this->seatHoldService->cleanupExpiredHolds();
        
        $this->info("Cleaned up {$cleaned} expired seat holds.");
        
        return Command::SUCCESS;
    }
}

