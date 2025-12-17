<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SeatHoldService;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeatHoldServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $seatHoldService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seatHoldService = new SeatHoldService();
        
        // Clear Redis before each test
        Redis::flushAll();
    }

    /** @test */
    public function it_can_hold_a_seat_successfully()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();
        $user = User::factory()->create();

        $result = $this->seatHoldService->holdSeat(
            $showtime->id,
            $seat->id,
            $user->id
        );

        $this->assertTrue($result);

        // Check Redis key exists
        $key = "seat_hold:{$showtime->id}:{$seat->id}";
        $this->assertTrue(Redis::exists($key) > 0);

        // Check TTL is 300 seconds
        $ttl = Redis::ttl($key);
        $this->assertEquals(300, $ttl);
    }

    /** @test */
    public function it_cannot_hold_an_already_held_seat()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User 1 holds the seat
        $this->seatHoldService->holdSeat($showtime->id, $seat->id, $user1->id);

        // User 2 tries to hold the same seat
        $result = $this->seatHoldService->holdSeat(
            $showtime->id,
            $seat->id,
            $user2->id
        );

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_release_a_held_seat()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();
        $user = User::factory()->create();

        // Hold the seat
        $this->seatHoldService->holdSeat($showtime->id, $seat->id, $user->id);

        // Release the seat
        $result = $this->seatHoldService->releaseSeat($showtime->id, $seat->id);

        $this->assertTrue($result);

        // Check Redis key is removed
        $key = "seat_hold:{$showtime->id}:{$seat->id}";
        $this->assertFalse(Redis::exists($key) > 0);
    }

    /** @test */
    public function it_can_check_if_seat_is_held()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();
        $user = User::factory()->create();

        // Initially not held
        $this->assertFalse(
            $this->seatHoldService->isSeatHeld($showtime->id, $seat->id)
        );

        // Hold the seat
        $this->seatHoldService->holdSeat($showtime->id, $seat->id, $user->id);

        // Now it should be held
        $this->assertTrue(
            $this->seatHoldService->isSeatHeld($showtime->id, $seat->id)
        );
    }

    /** @test */
    public function it_can_get_held_seats_for_showtime()
    {
        $showtime = Showtime::factory()->create();
        $seat1 = Seat::factory()->create();
        $seat2 = Seat::factory()->create();
        $seat3 = Seat::factory()->create();
        $user = User::factory()->create();

        // Hold 2 seats
        $this->seatHoldService->holdSeat($showtime->id, $seat1->id, $user->id);
        $this->seatHoldService->holdSeat($showtime->id, $seat2->id, $user->id);

        // Get held seats
        $heldSeats = $this->seatHoldService->getHeldSeats($showtime->id);

        $this->assertCount(2, $heldSeats);
        $this->assertArrayHasKey($seat1->id, $heldSeats);
        $this->assertArrayHasKey($seat2->id, $heldSeats);
        $this->assertArrayNotHasKey($seat3->id, $heldSeats);
    }

    /** @test */
    public function it_can_get_remaining_time_for_held_seat()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();
        $user = User::factory()->create();

        // Hold the seat
        $this->seatHoldService->holdSeat($showtime->id, $seat->id, $user->id);

        // Get remaining time
        $remainingTime = $this->seatHoldService->getRemainingTime(
            $showtime->id,
            $seat->id
        );

        $this->assertNotNull($remainingTime);
        $this->assertGreaterThan(0, $remainingTime);
        $this->assertLessThanOrEqual(300, $remainingTime);
    }

    /** @test */
    public function it_returns_null_for_remaining_time_if_seat_not_held()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();

        $remainingTime = $this->seatHoldService->getRemainingTime(
            $showtime->id,
            $seat->id
        );

        $this->assertNull($remainingTime);
    }
}

