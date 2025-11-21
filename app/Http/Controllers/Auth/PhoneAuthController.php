<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PhoneOtpNotification;
use App\Services\Sms\SpeedSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PhoneAuthController extends Controller
{
    public function __construct(private readonly SpeedSmsService $smsService)
    {
    }
    public function showPhoneLoginForm()
    {
        return view('auth.phone-login');
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
        ]);

        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'Không tìm thấy tài khoản với số điện thoại này.'])->withInput();
        }

        $otp = random_int(100000, 999999);
        $cacheKey = $this->getCacheKey($data['phone']);

        Cache::put($cacheKey, [
            'code' => $otp,
            'attempts' => 0,
        ], now()->addSeconds((int) env('OTP_EXPIRATION_SECONDS', 300)));

        $otpMessage = sprintf('Ma OTP dang nhap Cinema cua ban la %s (hieu luc %d phut).', $otp, (int) env('OTP_EXPIRATION_SECONDS', 300) / 60);
        $smsSent = $this->smsService->sendOtp($data['phone'], $otpMessage);

        if (!$smsSent) {
            $user->notify(new PhoneOtpNotification($otp));
        }

        return redirect()->route('login.phone.verify')->with([
            'phone' => $data['phone'],
            'status' => $smsSent
                ? 'Mã xác thực đã được gửi qua SMS.'
                : 'Không gửi được SMS, vui lòng kiểm tra hòm thư email để lấy mã OTP.',
        ]);
    }

    public function showVerifyForm()
    {
        $phone = session('phone');
        if (!$phone) {
            return redirect()->route('login.phone')->with('error', 'Vui lòng nhập số điện thoại trước.');
        }

        return view('auth.phone-verify', compact('phone'));
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        $cacheKey = $this->getCacheKey($data['phone']);
        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return back()->withErrors(['otp' => 'Mã xác thực đã hết hạn.'])->withInput();
        }

        $maxAttempts = (int) env('OTP_MAX_ATTEMPTS', 5);
        if ($otpData['attempts'] >= $maxAttempts) {
            Cache::forget($cacheKey);
            return back()->withErrors(['otp' => 'Bạn đã nhập sai quá số lần cho phép.'])->withInput();
        }

        if ((string) $otpData['code'] !== $data['otp']) {
            $otpData['attempts']++;
            Cache::put($cacheKey, $otpData, now()->addSeconds((int) env('OTP_EXPIRATION_SECONDS', 300)));
            return back()->withErrors(['otp' => 'Mã xác thực không đúng.'])->withInput();
        }

        Cache::forget($cacheKey);

        $user = User::where('phone', $data['phone'])->first();

        if (!$user) {
            return redirect()->route('login.phone')->withErrors(['phone' => 'Không tìm thấy tài khoản.']);
        }

        if (!$user->phone_verified_at) {
            $user->update(['phone_verified_at' => now()]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('home'))->with('success', 'Đăng nhập bằng số điện thoại thành công!');
    }

    protected function getCacheKey(string $phone): string
    {
        return 'otp_' . Str::lower($phone);
    }
}

