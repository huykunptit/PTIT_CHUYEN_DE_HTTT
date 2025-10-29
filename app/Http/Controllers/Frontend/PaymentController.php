<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{
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
        ]);
        
        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = route('payment.vnpay.return');
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        
        $vnp_TxnRef = $booking->booking_code;
        $vnp_OrderInfo = 'Thanh toan ve xem phim';
        $vnp_OrderType = 'other';
        $vnp_Amount = $booking->final_amount * 100; // VNPAY expects amount in cents
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $request->ip();
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        
        if (!empty($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        
        ksort($inputData);
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        return redirect($vnp_Url);
    }
    
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $vnp_TxnRef = $request->vnp_TxnRef;
        $vnp_Amount = $request->vnp_Amount;
        
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                $booking = Booking::where('booking_code', $vnp_TxnRef)->first();
                if ($booking) {
                    $booking->update([
                        'status' => 'CONFIRMED',
                        'payment_method' => 'VNPAY',
                        'payment_status' => 'SUCCESS',
                        'payment_details' => $request->all(),
                    ]);
                    
                    return redirect()->route('home')->with('success', 'Thanh toán thành công!');
                }
            }
        }
        
        return redirect()->route('home')->with('error', 'Thanh toán thất bại!');
    }
}
