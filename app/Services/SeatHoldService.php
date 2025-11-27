<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Events\SeatHeld;
use App\Events\SeatReleased;
use Carbon\Carbon;

class SeatHoldService
{
    const HOLD_TTL = 300; // 5 minutes in seconds
    const HOLD_PREFIX = 'seat_hold';

    /**
     * Hold a seat for a user
     *
     * @param int $showtimeId
     * @param int $seatId
     * @param int|null $userId
     * @return bool
     */
    public function holdSeat(int $showtimeId, int $seatId, ?int $userId = null): bool
    {
        $key = $this->getKey($showtimeId, $seatId);
        
        // Check if seat is already held
        if ($this->isSeatHeld($showtimeId, $seatId)) {
            return false;
        }

        $data = [
            'user_id' => $userId ?? auth()->id(),
            'held_at' => now()->toIso8601String(),
            'expires_at' => now()->addSeconds(self::HOLD_TTL)->toIso8601String(),
        ];

        // Set Redis key with TTL
        Redis::setex($key, self::HOLD_TTL, json_encode($data));

        // Broadcast event
        event(new SeatHeld($showtimeId, $seatId, $userId ?? auth()->id()));

        return true;
    }

    /**
     * Release a held seat
     *
     * @param int $showtimeId
     * @param int $seatId
     * @return bool
     */
    public function releaseSeat(int $showtimeId, int $seatId): bool
    {
        $key = $this->getKey($showtimeId, $seatId);
        
        if (!Redis::exists($key)) {
            return false;
        }

        $data = json_decode(Redis::get($key), true);
        $userId = $data['user_id'] ?? null;

        // Delete Redis key
        Redis::del($key);

        // Broadcast event
        event(new SeatReleased($showtimeId, $seatId, $userId));

        return true;
    }

    /**
     * Release multiple seats
     *
     * @param int $showtimeId
     * @param array $seatIds
     * @return void
     */
    public function releaseSeats(int $showtimeId, array $seatIds): void
    {
        foreach ($seatIds as $seatId) {
            $this->releaseSeat($showtimeId, $seatId);
        }
    }

    /**
     * Check if a seat is held
     *
     * @param int $showtimeId
     * @param int $seatId
     * @return bool
     */
    public function isSeatHeld(int $showtimeId, int $seatId): bool
    {
        $key = $this->getKey($showtimeId, $seatId);
        return Redis::exists($key) > 0;
    }

    /**
     * Get hold information for a seat
     *
     * @param int $showtimeId
     * @param int $seatId
     * @return array|null
     */
    public function getSeatHold(int $showtimeId, int $seatId): ?array
    {
        $key = $this->getKey($showtimeId, $seatId);
        
        if (!Redis::exists($key)) {
            return null;
        }

        $data = json_decode(Redis::get($key), true);
        
        if (!$data) {
            return null;
        }

        // Calculate remaining time
        $expiresAt = Carbon::parse($data['expires_at']);
        $data['remaining_seconds'] = max(0, now()->diffInSeconds($expiresAt, false));
        
        return $data;
    }

    /**
     * Get all held seats for a showtime
     *
     * @param int $showtimeId
     * @return array
     */
    public function getHeldSeats(int $showtimeId): array
    {
        $pattern = $this->getKey($showtimeId, '*');
        $keys = Redis::keys($pattern);
        
        $heldSeats = [];
        
        foreach ($keys as $key) {
            // Extract seat ID from key
            preg_match('/:(\d+)$/', $key, $matches);
            if (isset($matches[1])) {
                $seatId = (int) $matches[1];
                $data = $this->getSeatHold($showtimeId, $seatId);
                if ($data) {
                    $heldSeats[$seatId] = $data;
                }
            }
        }
        
        return $heldSeats;
    }

    /**
     * Get remaining time for a held seat
     *
     * @param int $showtimeId
     * @param int $seatId
     * @return int|null Seconds remaining, or null if not held
     */
    public function getRemainingTime(int $showtimeId, int $seatId): ?int
    {
        $hold = $this->getSeatHold($showtimeId, $seatId);
        
        if (!$hold) {
            return null;
        }

        return $hold['remaining_seconds'];
    }

    /**
     * Cleanup expired holds (called by scheduled job)
     *
     * @return int Number of expired holds cleaned up
     */
    public function cleanupExpiredHolds(): int
    {
        $pattern = $this->getKey('*', '*');
        $keys = Redis::keys($pattern);
        
        $cleaned = 0;
        
        foreach ($keys as $key) {
            // Check TTL - if <= 0, it's expired
            $ttl = Redis::ttl($key);
            
            if ($ttl <= 0) {
                // Extract showtime and seat IDs
                preg_match('/:(\d+):(\d+)$/', $key, $matches);
                if (isset($matches[1]) && isset($matches[2])) {
                    $showtimeId = (int) $matches[1];
                    $seatId = (int) $matches[2];
                    
                    // Get data before deleting
                    $data = json_decode(Redis::get($key), true);
                    $userId = $data['user_id'] ?? null;
                    
                    Redis::del($key);
                    
                    // Broadcast expired event
                    event(new \App\Events\SeatExpired($showtimeId, $seatId, $userId));
                    
                    $cleaned++;
                }
            }
        }
        
        return $cleaned;
    }

    /**
     * Get Redis key for seat hold
     *
     * @param int|string $showtimeId
     * @param int|string $seatId
     * @return string
     */
    private function getKey($showtimeId, $seatId): string
    {
        return self::HOLD_PREFIX . ":{$showtimeId}:{$seatId}";
    }
}

