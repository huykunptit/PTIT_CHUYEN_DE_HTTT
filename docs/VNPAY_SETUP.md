# 🔐 Hướng dẫn cấu hình VNPay Sandbox

## 📋 Thông tin VNPay Sandbox

```
VNPAY_TMN_CODE=56I0SGD8
VNPAY_HASH_SECRET=CTOXSBLUTLZ8DET7C81Z3AG7UU959UFL
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
```

## ⚙️ Cấu hình .env

Thêm hoặc cập nhật các dòng sau vào file `.env`:

```env
# VNPAY Configuration
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_TMN_CODE=56I0SGD8
VNPAY_HASH_SECRET=CTOXSBLUTLZ8DET7C81Z3AG7UU959UFL
VNPAY_RETURN_URL=http://localhost:8089/payment/vnpay/return
VNPAY_IPN_URL=http://localhost:8089/payment/vnpay/ipn
```

### Lưu ý:
- **VNPAY_RETURN_URL**: URL mà VNPay sẽ redirect về sau khi thanh toán
- **VNPAY_IPN_URL**: URL mà VNPay sẽ gọi để thông báo kết quả thanh toán (Instant Payment Notification)
- Nếu deploy lên server, thay `localhost:8089` bằng domain thực tế của bạn

## 🚀 Cách sử dụng

### 1. Cập nhật .env

```powershell
# Trong Docker
docker exec cinema_app nano .env

# Hoặc chỉnh sửa file .env trực tiếp
```

### 2. Clear config cache

```powershell
docker exec cinema_app php artisan config:clear
docker exec cinema_app php artisan config:cache
```

### 3. Test thanh toán

1. Tạo booking mới
2. Chọn phương thức thanh toán VNPay
3. Chọn một trong các phương thức:
   - **VNPay QR Code**: Quét mã QR để thanh toán
   - **VNPay ATM**: Thanh toán qua thẻ ATM nội địa
   - **VNPay Thẻ quốc tế**: Thanh toán qua thẻ quốc tế

### 4. Test với tài khoản sandbox

VNPay sandbox cung cấp các tài khoản test:

**Thẻ ATM nội địa:**
- Số thẻ: `9704198526191432198`
- Tên chủ thẻ: `NGUYEN VAN A`
- Ngày phát hành: `07/15`
- Mã OTP: `123456`

**Thẻ quốc tế:**
- Số thẻ: `4111111111111111`
- Tên chủ thẻ: `NGUYEN VAN A`
- Ngày hết hạn: `07/15`
- CVV: `123`

## 🔍 Kiểm tra logs

Nếu có lỗi, kiểm tra logs:

```powershell
# Xem logs Laravel
docker exec cinema_app tail -f storage/logs/laravel.log

# Xem logs trong code
# Logs được ghi tại:
# - app/Http/Controllers/Frontend/PaymentController.php
```

## 🐛 Troubleshooting

### Lỗi "Chữ ký không hợp lệ"

1. Kiểm tra `VNPAY_HASH_SECRET` đã đúng chưa
2. Kiểm tra `VNPAY_TMN_CODE` đã đúng chưa
3. Clear config cache: `php artisan config:clear && php artisan config:cache`

### Lỗi "Không tìm thấy đơn hàng"

1. Kiểm tra `booking_code` có khớp với `vnp_TxnRef` không
2. Kiểm tra booking có tồn tại trong database không

### Lỗi "Số tiền thanh toán không khớp"

1. Kiểm tra `final_amount` của booking
2. VNPay trả về số tiền đã nhân 100, code đã xử lý chia lại

### IPN không hoạt động

1. Kiểm tra `VNPAY_IPN_URL` có đúng không
2. Kiểm tra server có thể nhận request từ VNPay không
3. Kiểm tra firewall/security group có chặn request không

## 📝 Luồng thanh toán VNPay

1. **User chọn VNPay** → Click nút thanh toán
2. **Tạo payment URL** → `PaymentController@vnpay` tạo URL thanh toán
3. **Redirect đến VNPay** → User được chuyển đến trang thanh toán VNPay
4. **Thanh toán** → User nhập thông tin và thanh toán
5. **VNPay redirect về** → `PaymentController@vnpayReturn` xử lý kết quả
6. **VNPay gọi IPN** → `PaymentController@vnpayIpn` xác nhận thanh toán (nếu có)
7. **Cập nhật booking** → Status chuyển từ PENDING → CONFIRMED
8. **Gửi email** → Gửi email với PDF vé

## 🔒 Bảo mật

- ✅ Xác thực chữ ký (HMAC SHA512) cho mọi request từ VNPay
- ✅ Kiểm tra số tiền thanh toán khớp với booking
- ✅ Kiểm tra booking status trước khi xử lý
- ✅ Log tất cả các giao dịch để audit

## 📚 Tài liệu tham khảo

- [VNPay Documentation](https://sandbox.vnpayment.vn/apis/)
- [VNPay Sandbox](https://sandbox.vnpayment.vn/)

