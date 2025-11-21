# Hướng dẫn cấu hình SePay

## Bước 1: Cài đặt Package

Chạy lệnh sau để cài đặt package SePay:

```bash
composer require sepayvn/laravel-sepay
```

## Bước 2: Publish Migrations và Config

```bash
# Publish migrations
php artisan vendor:publish --tag="sepay-migrations"

# Chạy migrations
php artisan migrate

# Publish config
php artisan vendor:publish --tag="sepay-config"
```

## Bước 3: Cấu hình trong file .env

Thêm các biến môi trường sau vào file `.env`:

```env
# SePay Configuration
SEPAY_WEBHOOK_TOKEN=your_webhook_token_here
SEPAY_MATCH_PATTERN=SE
```

**Lưu ý:**
- `SEPAY_WEBHOOK_TOKEN`: Là API Key bạn sẽ tạo trong SePay dashboard (bước 4)
- `SEPAY_MATCH_PATTERN`: Mặc định là `SE`, bạn có thể thay đổi cho phù hợp

## Bước 4: Cấu hình Webhook trong SePay Dashboard

1. Đăng nhập vào [SePay Dashboard](https://sepay.vn)
2. Truy cập phần **Webhooks**
3. Bấm nút **Thêm Webhook** ở góc trên bên phải
4. Điền thông tin:
   - **URL**: `https://your-domain.com/api/sepay/webhook`
   - **Kiểu chứng thực**: Chọn **Api Key**
   - **API Key**: Nhập một dãy bí mật ngẫu nhiên (gồm chữ và số, không có dấu)
5. Copy API Key vừa tạo và dán vào file `.env` tại `SEPAY_WEBHOOK_TOKEN`

## Bước 5: Cấu hình Pattern trong SePay

Trong SePay dashboard, cấu hình pattern để SePay nhận diện booking code:
- Pattern: `SE` (hoặc pattern bạn đã cấu hình trong `.env`)
- Format: `SE{BOOKING_CODE}`

Ví dụ: Nếu booking_code là `BK12345678`, nội dung chuyển khoản sẽ là `SEBK12345678`

## Bước 6: Kiểm tra Webhook

Bạn có thể test webhook bằng Postman với request sau:

```bash
curl --location 'https://your-domain.com/api/sepay/webhook' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer your_webhook_token' \
--data '{
    "gateway": "MBBank",
    "transactionDate": "2024-05-25 21:11:02",
    "accountNumber": "0359123456",
    "subAccount": null,
    "code": null,
    "content": "Thanh toan QR SEBK12345678",
    "transferType": "in",
    "description": "Thanh toan QR SEBK12345678",
    "transferAmount": 170000,
    "referenceCode": "FT123456789",
    "accumulated": 0,
    "id": 123456
}'
```

**Lưu ý:** 
- Thay `SEBK12345678` bằng booking_code thực tế của bạn
- Thay `your_webhook_token` bằng token bạn đã cấu hình
- Thay `170000` bằng số tiền thực tế của booking

## Cách hoạt động

1. Khách hàng chọn phương thức thanh toán **Chuyển khoản ngân hàng** (SePay)
2. Hệ thống hiển thị thông tin:
   - Số tiền cần thanh toán
   - Nội dung chuyển khoản: `SE{BOOKING_CODE}`
3. Khách hàng chuyển khoản với nội dung đúng
4. SePay tự động phát hiện giao dịch và gửi webhook đến hệ thống
5. `SePayWebhookListener` xử lý webhook:
   - Tìm booking theo booking_code trong nội dung chuyển khoản
   - Kiểm tra số tiền
   - Cập nhật trạng thái booking thành `CONFIRMED`
   - Phát vé (cập nhật status tickets thành `SOLD`)

## Xử lý lỗi

Nếu webhook không hoạt động, kiểm tra:

1. **Logs**: Xem file `storage/logs/laravel.log` để xem chi tiết lỗi
2. **Webhook URL**: Đảm bảo URL webhook đúng và có thể truy cập từ internet
3. **API Key**: Kiểm tra API Key trong `.env` khớp với SePay dashboard
4. **Pattern**: Đảm bảo pattern trong `.env` khớp với cấu hình trong SePay
5. **Nội dung chuyển khoản**: Phải đúng format `SE{BOOKING_CODE}`

## Tài liệu tham khảo

- [SePay Laravel Package](https://github.com/sepayvn/laravel-sepay)
- [SePay Documentation](https://sepay.vn)

