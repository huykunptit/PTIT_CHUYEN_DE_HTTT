<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeatHoldService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SeatHoldController extends Controller
{
    protected $seatHoldService;

    public function __construct(SeatHoldService $seatHoldService)
    {
        $this->seatHoldService = $seatHoldService;
    }

    /**
     * Hold a seat
     */
    public function hold(Request $request): JsonResponse
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $showtimeId = $request->showtime_id;
        $seatId = $request->seat_id;

        // Check if seat is already held
        if ($this->seatHoldService->isSeatHeld($showtimeId, $seatId)) {
            return response()->json([
                'success' => false,
                'message' => 'Ghế này đã được giữ chỗ bởi người khác',
            ], 409);
        }

        // Hold the seat
        $held = $this->seatHoldService->holdSeat($showtimeId, $seatId, auth()->id());

        if (!$held) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể giữ chỗ ghế này',
            ], 400);
        }

        $holdInfo = $this->seatHoldService->getSeatHold($showtimeId, $seatId);

        return response()->json([
            'success' => true,
            'message' => 'Đã giữ chỗ ghế thành công',
            'data' => $holdInfo,
        ]);
    }

    /**
     * Release a seat
     */
    public function release(Request $request): JsonResponse
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $showtimeId = $request->showtime_id;
        $seatId = $request->seat_id;

        $released = $this->seatHoldService->releaseSeat($showtimeId, $seatId);

        if (!$released) {
            return response()->json([
                'success' => false,
                'message' => 'Ghế này không được giữ chỗ',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã giải phóng ghế',
        ]);
    }

    /**
     * Get all held seats for a showtime
     */
    public function getHeldSeats(Request $request, int $showtimeId): JsonResponse
    {
        $heldSeats = $this->seatHoldService->getHeldSeats($showtimeId);

        return response()->json([
            'success' => true,
            'data' => $heldSeats,
        ]);
    }

    /**
     * Check seat hold status
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $showtimeId = $request->showtime_id;
        $seatId = $request->seat_id;

        $isHeld = $this->seatHoldService->isSeatHeld($showtimeId, $seatId);
        $holdInfo = $isHeld ? $this->seatHoldService->getSeatHold($showtimeId, $seatId) : null;

        return response()->json([
            'success' => true,
            'is_held' => $isHeld,
            'hold_info' => $holdInfo,
        ]);
    }
}

