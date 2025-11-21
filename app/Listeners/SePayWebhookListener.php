<?php

namespace App\Listeners;

use App\Models\Booking;
use App\Events\PaymentSuccess;
use App\Events\BookingConfirmed;
use App\Notifications\PaymentSuccessNotification;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Support\Facades\Log;
use SePay\SePay\Events\SePayWebhookEvent;

class SePayWebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SePayWebhookEvent $event): void
    {
        $webhookData = $event->sePayWebhookData;
        
        // Xử lý tiền vào tài khoản (thanh toán thành công)
        if ($webhookData->transferType === 'in') {
            // Tìm booking theo pattern trong nội dung chuyển khoản
            // Pattern mặc định là "SE" + booking_code
            $content = $webhookData->content ?? '';
            
            // Tìm booking_code trong nội dung (ví dụ: "SEBK12345678")
            if (preg_match('/SE([A-Z0-9]+)/i', $content, $matches)) {
                $bookingCode = $matches[1];
                
                $booking = Booking::where('booking_code', $bookingCode)
                    ->where('status', 'PENDING')
                    ->first();
                
                if ($booking) {
                    // Kiểm tra số tiền
                    $amount = $webhookData->transferAmount ?? 0;
                    
                    if ($booking->final_amount == $amount) {
                        // Thanh toán thành công
                        $booking->update([
                            'status' => 'CONFIRMED',
                            'payment_method' => 'SePay - ' . ($webhookData->gateway ?? 'Chuyển khoản'),
                            'payment_status' => 'SUCCESS',
                            'payment_details' => [
                                'transaction_id' => $webhookData->referenceCode ?? null,
                                'gateway' => $webhookData->gateway ?? null,
                                'account_number' => $webhookData->accountNumber ?? null,
                                'transaction_date' => $webhookData->transactionDate ?? null,
                                'amount' => $amount,
                                'content' => $content,
                            ],
                        ]);

                        // Phát vé (Issue ticket)
                        $booking->tickets()->update([
                            'status' => 'SOLD',
                        ]);

                        // Reload booking với relationships
                        $booking->load(['user', 'showtime.movie', 'showtime.room.cinema']);

                        // Gửi notification và broadcast event
                        if ($booking->user) {
                            $booking->user->notify(new PaymentSuccessNotification($booking));
                            $booking->user->notify(new BookingConfirmedNotification($booking));
                        }
                        
                        // Broadcast events
                        event(new PaymentSuccess($booking));
                        event(new BookingConfirmed($booking));

                        Log::info('SePay: Payment success', [
                            'booking_id' => $booking->id,
                            'booking_code' => $bookingCode,
                            'amount' => $amount,
                            'reference_code' => $webhookData->referenceCode,
                        ]);
                    } else {
                        Log::warning('SePay: Amount mismatch', [
                            'booking_id' => $booking->id,
                            'booking_amount' => $booking->final_amount,
                            'received_amount' => $amount,
                        ]);
                    }
                } else {
                    Log::warning('SePay: Booking not found or already processed', [
                        'booking_code' => $bookingCode,
                        'content' => $content,
                    ]);
                }
            } else {
                Log::info('SePay: No booking code found in content', [
                    'content' => $content,
                    'info' => $event->info ?? null,
                ]);
            }
        } else {
            // Xử lý tiền ra tài khoản (nếu cần)
            Log::info('SePay: Outgoing transfer', [
                'transfer_type' => $webhookData->transferType,
                'amount' => $webhookData->transferAmount ?? 0,
            ]);
        }
    }
}

