<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    /**
     * Validate and apply promotion code
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $code = strtoupper(trim($request->code));
        $booking = Booking::findOrFail($request->booking_id);

        // Check if user owns this booking
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền sử dụng mã này cho booking này',
            ], 403);
        }

        // Find promotion
        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại',
            ], 404);
        }

        // Check if promotion is active
        if (!$promotion->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã bị vô hiệu hóa',
            ], 400);
        }

        // Check date validity
        $now = now();
        if ($now->lt($promotion->start_date) || $now->gt($promotion->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không còn hiệu lực',
            ], 400);
        }

        // Check usage limit
        if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng',
            ], 400);
        }

        // Check minimum amount
        if ($promotion->min_amount && $booking->total_amount < $promotion->min_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng phải có giá trị tối thiểu ' . number_format($promotion->min_amount, 0, ',', '.') . '₫',
            ], 400);
        }

        // Check per user limit
        $userUsageCount = $promotion->usages()
            ->where('user_id', Auth::id())
            ->count();

        if ($userUsageCount >= $promotion->per_user_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng hết lượt sử dụng mã này',
            ], 400);
        }

        // Check if already used for this booking
        if ($booking->promotions()->where('promotions.id', $promotion->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã được áp dụng cho đơn hàng này',
            ], 400);
        }

        // Calculate discount
        $discountAmount = 0;
        
        if ($promotion->type === 'PERCENTAGE') {
            $discountAmount = ($booking->total_amount * $promotion->value) / 100;
            if ($promotion->max_discount) {
                $discountAmount = min($discountAmount, $promotion->max_discount);
            }
        } elseif ($promotion->type === 'FIXED_AMOUNT') {
            $discountAmount = min($promotion->value, $booking->total_amount);
        } elseif ($promotion->type === 'FREE_TICKET') {
            // Free ticket logic (có thể implement sau)
            $discountAmount = 0;
        }

        $finalAmount = max(0, $booking->total_amount - $discountAmount);

        return response()->json([
            'success' => true,
            'message' => 'Mã giảm giá hợp lệ',
            'data' => [
                'promotion' => [
                    'id' => $promotion->id,
                    'code' => $promotion->code,
                    'name' => $promotion->name,
                    'type' => $promotion->type,
                    'value' => $promotion->value,
                ],
                'discount_amount' => $discountAmount,
                'total_amount' => $booking->total_amount,
                'final_amount' => $finalAmount,
            ],
        ]);
    }
}

