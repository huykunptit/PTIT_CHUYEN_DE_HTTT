# Hướng dẫn cấu hình Push Notifications và Email

## 1. Cấu hình Email SMTP

### Gmail SMTP

Thêm vào file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cinema.com"
MAIL_FROM_NAME="Cinema"
```

**Lưu ý:** 
- Cần tạo App Password cho Gmail: https://myaccount.google.com/apppasswords
- Hoặc sử dụng OAuth2 nếu cần bảo mật cao hơn

### Outlook/Hotmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### SendGrid SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### Mailgun SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-mailgun-username
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
```

## 2. Cấu hình Pusher cho Realtime Notifications

### Đăng ký tài khoản Pusher

1. Truy cập https://pusher.com
2. Đăng ký tài khoản miễn phí
3. Tạo app mới
4. Lấy App ID, Key, Secret, và Cluster

### Cấu hình .env

```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Alternative: Sử dụng Laravel Websockets (Self-hosted)

Nếu không muốn dùng Pusher, có thể sử dụng Laravel Websockets:

```bash
composer require beyondcode/laravel-websockets
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
php artisan migrate
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
```

Cấu hình `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

## 3. Chạy Queue Worker

Notifications được gửi qua queue để không block request:

```bash
# Development
php artisan queue:work

# Production (với Supervisor)
# Xem file DEPLOY.md để cấu hình Supervisor
```

## 4. Kiểm tra hoạt động

### Test Email

```bash
php artisan tinker
>>> Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')->subject('Test');
});
```

### Test Notifications

1. Tạo booking và thanh toán thành công
2. Kiểm tra email có được gửi không
3. Kiểm tra notification có hiển thị realtime không

## 5. Các loại Notifications

### BookingConfirmedNotification
- Gửi khi booking được xác nhận
- Gửi email và lưu vào database
- Broadcast realtime event

### PaymentSuccessNotification
- Gửi khi thanh toán thành công
- Gửi email và lưu vào database
- Broadcast realtime event

## 6. Frontend Notifications

Notifications sẽ hiển thị:
- Icon bell trong navbar với badge số lượng unread
- Dropdown menu với danh sách notifications
- Toast notification khi có event mới
- Auto refresh mỗi 30 giây

## 7. Troubleshooting

### Email không gửi được

1. Kiểm tra SMTP credentials trong .env
2. Kiểm tra queue worker đang chạy: `php artisan queue:work`
3. Xem logs: `tail -f storage/logs/laravel.log`
4. Test với Mailtrap (development): https://mailtrap.io

### Pusher không hoạt động

1. Kiểm tra PUSHER credentials trong .env
2. Kiểm tra broadcasting auth route
3. Mở browser console để xem lỗi
4. Kiểm tra CORS settings nếu cần

### Notifications không hiển thị

1. Kiểm tra user đã đăng nhập
2. Kiểm tra Pusher connection trong browser console
3. Kiểm tra channel subscription
4. Kiểm tra events có được broadcast không

## 8. Production Checklist

- [ ] Cấu hình SMTP production (SendGrid, Mailgun, etc.)
- [ ] Cấu hình Pusher hoặc Laravel Websockets
- [ ] Queue worker đang chạy với Supervisor
- [ ] Test email gửi thành công
- [ ] Test notifications realtime
- [ ] Monitor queue jobs
- [ ] Set up email monitoring (bounce, spam)

