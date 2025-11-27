<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Services\VnPayService;
use App\Events\PaymentSuccess;
use App\Events\BookingConfirmed;
use App\Notifications\PaymentSuccessNotification;
use App\Notifications\BookingConfirmedNotification;
use App\Mail\TicketPdfMail;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VnPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    public function index(Booking $booking)
    {
        if ($booking->status !== 'PENDING') {
            return redirect()->route('home')->with('error', 'Booking không hợp lệ');
        }
        
        return view('frontend.payment.index', compact('booking'));
    }
    
    public function vnpay(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:vnpay_qr,vnpay_atm,vnpay_card',
            'promotion_id' => 'nullable|exists:promotions,id',
        ]);
        
        if ($booking->status !== 'PENDING') {
            return redirect()->route('home')->with('error', 'Booking không hợp lệ');
        }

        // Apply promotion nếu có
        if ($request->promotion_id) {
            $this->applyPromotion($booking, $request->promotion_id);
        }

        // Tạo URL thanh toán VNPay
        $vnp_TxnRef = $booking->booking_code; // Sử dụng booking_code làm mã giao dịch
        $vnp_Amount = $booking->fresh()->final_amount; // Reload để lấy final_amount mới nhất
        $vnp_OrderInfo = "Thanh toan don hang: " . $booking->booking_code;
        $vnp_BankCode = $this->vnpayService->getBankCode($request->payment_method);
        $vnp_Locale = 'vn';

        $paymentUrl = $this->vnpayService->createPaymentUrl([
            'vnp_TxnRef' => $vnp_TxnRef,
            'vnp_Amount' => $vnp_Amount,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_BankCode' => $vnp_BankCode,
            'vnp_Locale' => $vnp_Locale,
            'vnp_IpAddr' => $request->ip(),
        ]);

        // Lưu payment method vào booking
        $paymentMethods = [
            'vnpay_qr' => 'VNPAY QR Code',
            'vnpay_atm' => 'VNPAY ATM',
            'vnpay_card' => 'VNPAY Thẻ quốc tế',
        ];
        
        $booking->update([
            'payment_method' => $paymentMethods[$request->payment_method] ?? 'VNPAY',
        ]);

        // Redirect đến VNPay
        return redirect($paymentUrl);
    }
    
    /**
     * Trang thanh toán demo (không cần VNPay thật)
     */
    public function demo(Request $request, Booking $booking)
    {
        if ($booking->status !== 'PENDING') {
            return redirect()->route('home')->with('error', 'Booking không hợp lệ');
        }
        
        $method = $request->get('method', 'vnpay_qr');
        
        return view('frontend.payment.demo', compact('booking', 'method'));
    }
    
    /**
     * Xử lý thanh toán demo (simulate payment success)
     */
    public function processDemo(Request $request, Booking $booking)
    {
        if ($booking->status !== 'PENDING') {
            return redirect()->route('home')->with('error', 'Booking không hợp lệ');
        }
        
        $method = $request->input('method', $request->query('method', 'vnpay_qr'));
        $action = $request->input('action', $request->query('action', 'success')); // success hoặc cancel
        
        if ($action === 'success') {
            // Simulate payment success
            $paymentMethods = [
                'vnpay_qr' => 'VNPAY QR Code',
                'vnpay_atm' => 'VNPAY ATM',
                'vnpay_card' => 'VNPAY Thẻ quốc tế',
            ];
            
            $booking->update([
                'status' => 'CONFIRMED',
                'payment_method' => $paymentMethods[$method] ?? 'VNPAY',
                'payment_status' => 'SUCCESS',
                'expires_at' => null, // Clear expiration date after successful payment
                'payment_details' => [
                    'transaction_id' => 'DEMO' . strtoupper(\Illuminate\Support\Str::random(12)),
                    'method' => $method,
                    'paid_at' => now()->toIso8601String(),
                ],
            ]);
            
            // Phát vé (Issue ticket) - Cập nhật trạng thái vé
            $booking->tickets()->update([
                'status' => 'SOLD',
            ]);
            
            // Reload booking với relationships
            $booking->load(['user', 'showtime.movie', 'showtime.room.cinema', 'tickets', 'promotions']);
            
            // Save promotion usage
            $this->savePromotionUsage($booking);
            
            // Gửi notification và email PDF
            if ($booking->user) {
                $booking->user->notify(new PaymentSuccessNotification($booking));
                $booking->user->notify(new BookingConfirmedNotification($booking));
                
                // Gửi email PDF cho mỗi ticket
                foreach ($booking->tickets as $ticket) {
                    Mail::to($booking->user->email)->send(new TicketPdfMail($ticket));
                }
            }
            
            // Broadcast events
            event(new PaymentSuccess($booking));
            event(new BookingConfirmed($booking));
            
            // Redirect đến trang hiển thị vé
            return redirect()->route('tickets.show', ['booking' => $booking->id])
                ->with('success', 'Thanh toán thành công! Vui lòng lưu mã vé để check-in.');
            } else {
            // User cancelled payment
            return redirect()->route('payment.index', $booking)
                ->with('info', 'Bạn đã hủy thanh toán. Vui lòng thanh toán trước khi hết hạn.');
        }
    }
    
    /**
     * Xử lý khi VNPay redirect về sau khi thanh toán
     */
    public function vnpayReturn(Request $request)
    {
        // Xác thực chữ ký
        if (!$this->vnpayService->validateSignature($request)) {
            Log::error('VNPay return: Invalid signature', $request->all());
            return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ!');
        }

        // Lấy dữ liệu từ VNPay
        $returnData = $this->vnpayService->getReturnData($request);
        $vnp_TxnRef = $returnData['vnp_TxnRef'];
        $vnp_ResponseCode = $returnData['vnp_ResponseCode'];

        // Tìm booking theo booking_code
        $booking = Booking::where('booking_code', $vnp_TxnRef)->first();

        if (!$booking) {
            Log::error('VNPay return: Booking not found', ['booking_code' => $vnp_TxnRef]);
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Kiểm tra số tiền
        if ($booking->final_amount != $returnData['vnp_Amount']) {
            Log::error('VNPay return: Amount mismatch', [
                'booking_amount' => $booking->final_amount,
                'vnpay_amount' => $returnData['vnp_Amount']
            ]);
            return redirect()->route('payment.index', $booking)
                ->with('error', 'Số tiền thanh toán không khớp!');
        }

        // Xử lý kết quả thanh toán
        if ($vnp_ResponseCode == '00') {
            // Thanh toán thành công
            if ($booking->status == 'PENDING') {
                $booking->update([
                    'status' => 'CONFIRMED',
                    'payment_status' => 'SUCCESS',
                    'expires_at' => null, // Clear expiration date after successful payment
                    'payment_details' => $returnData['all'],
                ]);

                // Phát vé (Issue ticket) - Cập nhật trạng thái vé
                $booking->tickets()->update([
                    'status' => 'SOLD',
                ]);

                // Reload booking với relationships
                $booking->load(['user', 'showtime.movie', 'showtime.room.cinema']);

                // Save promotion usage
                $this->savePromotionUsage($booking);

                // Gửi notification và broadcast event
                if ($booking->user) {
                    $booking->user->notify(new PaymentSuccessNotification($booking));
                    $booking->user->notify(new BookingConfirmedNotification($booking));
                    
                    // Gửi email PDF cho mỗi ticket
                    foreach ($booking->tickets as $ticket) {
                        Mail::to($booking->user->email)->send(new TicketPdfMail($ticket));
                    }
                }
                
                // Broadcast events
                event(new PaymentSuccess($booking));
                event(new BookingConfirmed($booking));

                Log::info('VNPay return: Payment success', ['booking_id' => $booking->id]);

                return redirect()->route('tickets.show', ['booking' => $booking->id])
                    ->with('success', 'Thanh toán thành công! Vui lòng lưu mã vé để check-in.');
            } else {
                // Booking đã được xử lý trước đó
                return redirect()->route('tickets.show', ['booking' => $booking->id])
                    ->with('info', 'Đơn hàng đã được xử lý trước đó.');
            }
        } else {
            // Thanh toán thất bại
            $booking->update([
                'payment_status' => 'FAILED',
                'payment_details' => $returnData['all'],
            ]);

            $errorMessages = [
                '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường).',
                '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking',
                '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
                '11' => 'Đã hết hạn chờ thanh toán. Xin vui lòng thực hiện lại giao dịch.',
                '12' => 'Thẻ/Tài khoản bị khóa.',
                '13' => 'Nhập sai mật khẩu xác thực giao dịch (OTP). Xin vui lòng thực hiện lại giao dịch.',
                '51' => 'Tài khoản không đủ số dư để thực hiện giao dịch.',
                '65' => 'Tài khoản đã vượt quá hạn mức giao dịch trong ngày.',
                '75' => 'Ngân hàng thanh toán đang bảo trì.',
                '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định.',
            ];

            $errorMessage = $errorMessages[$vnp_ResponseCode] ?? 'Thanh toán thất bại!';

            Log::warning('VNPay return: Payment failed', [
                'booking_id' => $booking->id,
                'response_code' => $vnp_ResponseCode
            ]);

            return redirect()->route('payment.index', $booking)
                ->with('error', $errorMessage);
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPay
     */
    public function vnpayIpn(Request $request)
    {
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        
        // Xác thực chữ ký
        if (!$this->vnpayService->validateIpnSignature($inputData, $vnp_SecureHash)) {
            Log::error('VNPay IPN: Invalid signature', $inputData);
            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ], 200);
        }

        $vnpTranId = $inputData['vnp_TransactionNo'] ?? '';
        $vnp_BankCode = $inputData['vnp_BankCode'] ?? '';
        $vnp_Amount = ($inputData['vnp_Amount'] ?? 0) / 100; // Chia 100 vì VNPay gửi số tiền nhân 100
        $orderId = $inputData['vnp_TxnRef'] ?? '';
        $vnp_ResponseCode = $inputData['vnp_ResponseCode'] ?? '';
        $vnp_TransactionStatus = $inputData['vnp_TransactionStatus'] ?? '';

        // Tìm booking
        $booking = Booking::where('booking_code', $orderId)->first();

        if (!$booking) {
            Log::error('VNPay IPN: Booking not found', ['booking_code' => $orderId]);
            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found'
            ], 200);
        }

        // Kiểm tra số tiền
        if ($booking->final_amount != $vnp_Amount) {
            Log::error('VNPay IPN: Amount mismatch', [
                'booking_amount' => $booking->final_amount,
                'vnpay_amount' => $vnp_Amount
            ]);
            return response()->json([
                'RspCode' => '04',
                'Message' => 'Invalid amount'
            ], 200);
        }

        // Kiểm tra trạng thái booking
        if ($booking->status != 'PENDING') {
            Log::info('VNPay IPN: Booking already processed', [
                'booking_id' => $booking->id,
                'status' => $booking->status
            ]);
            return response()->json([
                'RspCode' => '02',
                'Message' => 'Order already confirmed'
            ], 200);
        }

        // Xử lý kết quả thanh toán
        if ($vnp_ResponseCode == '00' && $vnp_TransactionStatus == '00') {
            // Thanh toán thành công
                    $booking->update([
                        'status' => 'CONFIRMED',
                        'payment_status' => 'SUCCESS',
                        'expires_at' => null, // Clear expiration date after successful payment
                'payment_details' => $inputData,
                    ]);
                    
            // Phát vé (Issue ticket)
                    $booking->tickets()->update([
                        'status' => 'SOLD',
                    ]);
                    
            // Reload booking với relationships
            $booking->load(['user', 'showtime.movie', 'showtime.room.cinema', 'promotions']);

            // Save promotion usage
            $this->savePromotionUsage($booking);

            // Gửi notification và broadcast event
            if ($booking->user) {
                $booking->user->notify(new PaymentSuccessNotification($booking));
                $booking->user->notify(new BookingConfirmedNotification($booking));
                
                // Gửi email PDF cho mỗi ticket
                foreach ($booking->tickets as $ticket) {
                    Mail::to($booking->user->email)->send(new TicketPdfMail($ticket));
                }
            }
            
            // Broadcast events
            event(new PaymentSuccess($booking));
            event(new BookingConfirmed($booking));

            Log::info('VNPay IPN: Payment success', [
                'booking_id' => $booking->id,
                'transaction_id' => $vnpTranId
            ]);

            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ], 200);
        } else {
            // Thanh toán thất bại
            $booking->update([
                'payment_status' => 'FAILED',
                'payment_details' => $inputData,
            ]);

            Log::warning('VNPay IPN: Payment failed', [
                'booking_id' => $booking->id,
                'response_code' => $vnp_ResponseCode,
                'transaction_status' => $vnp_TransactionStatus
            ]);

            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ], 200);
        }
    }

    /**
     * Apply promotion to booking
     */
    protected function applyPromotion(Booking $booking, int $promotionId): void
    {
        $promotion = Promotion::findOrFail($promotionId);
        
        // Validate promotion (similar to PromotionController)
        if (!$promotion->is_active) {
            return;
        }

        $now = now();
        if ($now->lt($promotion->start_date) || $now->gt($promotion->end_date)) {
            return;
        }

        if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) {
            return;
        }

        if ($promotion->min_amount && $booking->total_amount < $promotion->min_amount) {
            return;
        }

        // Check if already applied
        if ($booking->promotions()->where('promotions.id', $promotionId)->exists()) {
            return;
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
        }

        $finalAmount = max(0, $booking->total_amount - $discountAmount);

        // Update booking
        $booking->update([
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
        ]);

        // Attach promotion
        $booking->promotions()->attach($promotionId, [
            'user_id' => $booking->user_id,
            'discount_amount' => $discountAmount,
        ]);

        // Increment usage count
        $promotion->increment('usage_count');
    }

    /**
     * Save promotion usage after successful payment
     */
    protected function savePromotionUsage(Booking $booking): void
    {
        // Promotion usage đã được lưu khi apply promotion
        // Chỉ cần đảm bảo promotion_usage được tạo với booking_id
        foreach ($booking->promotions as $promotion) {
            DB::table('promotion_usages')
                ->where('booking_id', $booking->id)
                ->where('promotion_id', $promotion->id)
                ->update([
                    'booking_id' => $booking->id,
                    'updated_at' => now(),
                ]);
        }
    }
}
