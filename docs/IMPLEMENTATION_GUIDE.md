# Hướng dẫn triển khai các tính năng mới

## 1. Cài đặt Dependencies

```bash
# PDF Generation
composer require barryvdh/laravel-dompdf

# Publish config (nếu cần)
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## 2. Redis Seat-Hold Mechanism

✅ **Đã hoàn thành:**
- `app/Services/SeatHoldService.php` - Service quản lý seat holds
- `app/Events/SeatHeld.php`, `SeatReleased.php`, `SeatExpired.php` - Events
- `app/Http/Controllers/Api/SeatHoldController.php` - API endpoints
- `app/Console/Commands/CleanupExpiredSeatHolds.php` - Scheduled command
- Frontend realtime updates với Pusher

**Cần chạy:**
```bash
# Thêm vào crontab hoặc supervisor để chạy scheduled tasks
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 3. PDF Ticket Generation

✅ **Đã hoàn thành:**
- `resources/views/pdf/ticket.blade.php` - PDF template
- `app/Mail/TicketPdfMail.php` - Mailable với PDF attachment
- `resources/views/emails/ticket-pdf.blade.php` - Email template
- Tích hợp vào PaymentController

**Cần chạy:**
```bash
composer require barryvdh/laravel-dompdf
```

## 4. MoMo Payment Integration

⚠️ **Cần triển khai:**

### Bước 1: Cài đặt MoMo SDK
```bash
# Tải MoMo PHP SDK từ: https://developers.momo.vn/
# Hoặc sử dụng package: composer require momo/payment
```

### Bước 2: Tạo MoMoService
Tạo file `app/Services/MoMoService.php` tương tự `VnPayService.php`

### Bước 3: Thêm routes
Thêm vào `routes/web.php`:
```php
Route::post('/payment/momo', [PaymentController::class, 'momo'])->name('payment.momo');
Route::get('/payment/momo/return', [PaymentController::class, 'momoReturn'])->name('payment.momo.return');
Route::post('/payment/momo/ipn', [PaymentController::class, 'momoIpn'])->name('payment.momo.ipn');
```

### Bước 4: Cập nhật PaymentController
Thêm methods: `momo()`, `momoReturn()`, `momoIpn()`

### Bước 5: Cập nhật UI
Thêm option chọn MoMo trong payment page

## 5. Countdown Timer

⚠️ **Cần triển khai:**
- Thêm countdown timer vào payment page
- Hiển thị thời gian còn lại để thanh toán
- Warning khi sắp hết hạn

## 6. Auto-release Bookings

⚠️ **Cần triển khai:**
- Scheduled job để expire bookings quá hạn
- Tự động chuyển status PENDING → EXPIRED
- Giải phóng ghế khi booking expires

## 7. Promotion trong Checkout

⚠️ **Cần triển khai:**
- Form nhập mã giảm giá trong payment page
- API validate mã
- Tính toán discount
- Lưu promotion_usage

---

## Checklist triển khai

- [x] Redis Seat-Hold Service
- [x] Realtime Seat Updates
- [x] PDF Ticket Generation
- [ ] MoMo Payment (cần SDK)
- [ ] Countdown Timer
- [ ] Auto-release Bookings
- [ ] Promotion trong Checkout

