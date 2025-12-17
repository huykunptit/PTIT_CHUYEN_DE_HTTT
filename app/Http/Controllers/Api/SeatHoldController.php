<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeatHoldService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Seats",
 *     description="API endpoints for seat holding management"
 * )
 */
class SeatHoldController extends Controller
{
    protected $seatHoldService;

    public function __construct(SeatHoldService $seatHoldService)
    {
        $this->seatHoldService = $seatHoldService;
    }

    /**
     * @OA\Post(
     *     path="/api/seats/hold",
     *     summary="Giữ chỗ ghế",
     *     tags={"Seats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"showtime_id", "seat_id"},
     *             @OA\Property(property="showtime_id", type="integer", example=1),
     *             @OA\Property(property="seat_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Giữ chỗ thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=409, description="Ghế đã được giữ chỗ"),
     *     @OA\Response(response=400, description="Lỗi validation")
     * )
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
     * @OA\Post(
     *     path="/api/seats/release",
     *     summary="Giải phóng ghế",
     *     tags={"Seats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"showtime_id", "seat_id"},
     *             @OA\Property(property="showtime_id", type="integer", example=1),
     *             @OA\Property(property="seat_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Giải phóng thành công"),
     *     @OA\Response(response=404, description="Ghế không được giữ chỗ")
     * )
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
     * @OA\Get(
     *     path="/api/seats/showtime/{showtimeId}/held",
     *     summary="Lấy danh sách ghế đã được giữ chỗ",
     *     tags={"Seats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="showtimeId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/seats/check-status",
     *     summary="Kiểm tra trạng thái giữ chỗ ghế",
     *     tags={"Seats"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"showtime_id", "seat_id"},
     *             @OA\Property(property="showtime_id", type="integer", example=1),
     *             @OA\Property(property="seat_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="is_held", type="boolean", example=false),
     *             @OA\Property(property="hold_info", type="object", nullable=true)
     *         )
     *     )
     * )
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

