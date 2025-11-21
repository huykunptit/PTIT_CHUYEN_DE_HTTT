<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                    'password' => bcrypt(Str::random(16)),
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended(route('home'))->with('success', 'Đăng nhập Google thành công!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }
}

