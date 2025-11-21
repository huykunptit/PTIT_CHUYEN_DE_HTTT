# Cài đặt Push Notifications và Email

## Bước 1: Cài đặt Package

```bash
composer require pusher/pusher-php-server
```

## Bước 2: Chạy Migrations

```bash
php artisan migrate
```

Migration này sẽ tạo bảng `notifications` để lưu notifications trong database.

## Bước 3: Cấu hình .env

### Email SMTP

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

### Pusher (Realtime Notifications)

1. Đăng ký tại https://pusher.com (miễn phí)
2. Tạo app mới
3. Lấy credentials và thêm vào `.env`:

```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

## Bước 4: Chạy Queue Worker

Notifications được gửi qua queue để không block request:

```bash
# Development
php artisan queue:work

# Hoặc sử dụng queue:listen để auto-reload
php artisan queue:listen
```

## Bước 5: Test

### Test Email

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')->subject('Test Email');
});
```

### Test Notifications

1. Tạo một booking
2. Thanh toán thành công
3. Kiểm tra:
   - Email có được gửi không
   - Notification có hiển thị trong navbar không
   - Realtime notification có popup không

## Tính năng

### Email Notifications
- ✅ BookingConfirmedNotification - Gửi khi booking được xác nhận
- ✅ PaymentSuccessNotification - Gửi khi thanh toán thành công
- ✅ Email được gửi qua queue (không block)
- ✅ Email có format đẹp với thông tin đầy đủ

### Realtime Push Notifications
- ✅ Hiển thị realtime khi có event mới
- ✅ Badge số lượng unread notifications
- ✅ Dropdown menu với danh sách notifications
- ✅ Toast notification popup
- ✅ Auto refresh mỗi 30 giây
- ✅ Admin notifications riêng

## API Endpoints

- `GET /api/notifications` - Lấy danh sách notifications
- `GET /api/notifications/unread-count` - Lấy số lượng unread
- `POST /api/notifications/{id}/read` - Đánh dấu đã đọc
- `POST /api/notifications/mark-all-read` - Đánh dấu tất cả đã đọc

## Events

- `BookingConfirmed` - Broadcast khi booking được xác nhận
- `PaymentSuccess` - Broadcast khi thanh toán thành công

## Channels

- `private-notifications.{userId}` - Channel cho từng user
- `private-admin.notifications` - Channel cho admin

