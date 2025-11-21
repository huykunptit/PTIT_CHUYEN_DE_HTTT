# SSO & OTP Setup Guide

## 1. Cài đặt packages

```bash
composer require laravel/socialite
composer require simplesoftwareio/simple-qrcode
```

## 2. Cấu hình Google OAuth

Tạo ứng dụng trên [Google Cloud Console](https://console.cloud.google.com/).

Thêm vào `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URL=https://your-domain.com/auth/google/callback
```

## 3. Cấu hình OTP

Trong `.env`:

```env
OTP_EXPIRATION_SECONDS=300
OTP_MAX_ATTEMPTS=5
```

Hiện tại OTP được gửi qua email bằng `PhoneOtpNotification`. Bạn có thể thay bằng SMS Provider sau.

## 4. Chạy migration

```bash
php artisan migrate
```

## 5. Queue worker

OTP notification sử dụng queue:

```bash
php artisan queue:work
```

## 6. Routes

- `GET /auth/google/redirect`
- `GET /auth/google/callback`
- `GET /login/phone`
- `POST /login/phone/send`
- `GET /login/phone/verify`
- `POST /login/phone/verify`

## 7. Frontend

- Nút Google & Phone login đã có trong `resources/views/auth/login.blade.php`
- Trang nhập phone: `resources/views/auth/phone-login.blade.php`
- Trang nhập OTP: `resources/views/auth/phone-verify.blade.php`


