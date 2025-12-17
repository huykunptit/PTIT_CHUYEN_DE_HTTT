<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Showtime;
use App\Models\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;

class SeatHoldTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::flushAll();
    }

    /** @test */
    public function authenticated_user_can_hold_seat()
    {
        $user = User::factory()->create();
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();

        $response = $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Đã giữ chỗ ghế thành công',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user_id',
                    'held_at',
                    'expires_at',
                    'remaining_seconds',
                ],
            ]);

        // Check Redis key exists
        $key = "seat_hold:{$showtime->id}:{$seat->id}";
        $this->assertTrue(Redis::exists($key) > 0);
    }

    /** @test */
    public function unauthenticated_user_cannot_hold_seat()
    {
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();

        $response = $this->postJson('/api/seats/hold', [
            'showtime_id' => $showtime->id,
            'seat_id' => $seat->id,
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function user_cannot_hold_already_held_seat()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();

        // User 1 holds the seat
        $this->actingAs($user1, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        // User 2 tries to hold the same seat
        $response = $this->actingAs($user2, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Ghế này đã được giữ chỗ bởi người khác',
            ]);
    }

    /** @test */
    public function user_can_release_held_seat()
    {
        $user = User::factory()->create();
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();

        // Hold the seat first
        $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        // Release the seat
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/seats/release', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Đã giải phóng ghế',
            ]);

        // Check Redis key is removed
        $key = "seat_hold:{$showtime->id}:{$seat->id}";
        $this->assertFalse(Redis::exists($key) > 0);
    }

    /** @test */
    public function user_can_get_held_seats_for_showtime()
    {
        $user = User::factory()->create();
        $showtime = Showtime::factory()->create();
        $seat1 = Seat::factory()->create();
        $seat2 = Seat::factory()->create();

        // Hold 2 seats
        $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat1->id,
            ]);

        $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat2->id,
            ]);

        // Get held seats
        $response = $this->actingAs($user, 'web')
            ->getJson("/api/seats/showtime/{$showtime->id}/held");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'user_id',
                        'held_at',
                        'expires_at',
                    ],
                ],
            ]);

        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    /** @test */
    public function user_can_check_seat_status()
    {
        $user = User::factory()->create();
        $showtime = Showtime::factory()->create();
        $seat = Seat::factory()->create();

        // Initially not held
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/seats/check-status', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_held' => false,
                'hold_info' => null,
            ]);

        // Hold the seat
        $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        // Check status again
        $response = $this->actingAs($user, 'web')
            ->postJson('/api/seats/check-status', [
                'showtime_id' => $showtime->id,
                'seat_id' => $seat->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_held' => true,
            ])
            ->assertJsonStructure([
                'success',
                'is_held',
                'hold_info' => [
                    'user_id',
                    'held_at',
                    'expires_at',
                ],
            ]);
    }

    /** @test */
    public function hold_seat_requires_valid_showtime()
    {
        $user = User::factory()->create();
        $seat = Seat::factory()->create();

        $response = $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => 99999, // Non-existent
                'seat_id' => $seat->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['showtime_id']);
    }

    /** @test */
    public function hold_seat_requires_valid_seat()
    {
        $user = User::factory()->create();
        $showtime = Showtime::factory()->create();

        $response = $this->actingAs($user, 'web')
            ->postJson('/api/seats/hold', [
                'showtime_id' => $showtime->id,
                'seat_id' => 99999, // Non-existent
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['seat_id']);
    }
}

