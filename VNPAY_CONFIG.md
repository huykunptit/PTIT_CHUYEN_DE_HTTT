# ⚡ Cấu hình VNPay Sandbox - Quick Start

## 🔧 Cập nhật .env

Thêm các dòng sau vào file `.env`:

```env
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_TMN_CODE=56I0SGD8
VNPAY_HASH_SECRET=CTOXSBLUTLZ8DET7C81Z3AG7UU959UFL
VNPAY_RETURN_URL=http://localhost:8089/payment/vnpay/return
VNPAY_IPN_URL=http://localhost:8089/payment/vnpay/ipn
```

## 🚀 Các bước thực hiện

### 1. Cập nhật .env

```powershell
# Cách 1: Sửa trực tiếp file .env
# Thêm các dòng trên vào file .env

# Cách 2: Sử dụng Docker
docker exec -it cinema_app sh
nano .env
# Thêm các dòng trên, sau đó Ctrl+X, Y, Enter để lưu
```

### 2. Clear config cache

```powershell
docker exec cinema_app php artisan config:clear
docker exec cinema_app php artisan config:cache
```

### 3. Restart services (nếu cần)

```powershell
docker-compose restart app
```

## ✅ Kiểm tra

1. Tạo booking mới
2. Chọn thanh toán VNPay
3. Chọn phương thức thanh toán (QR/ATM/Card)
4. Bạn sẽ được redirect đến trang VNPay sandbox

## 🧪 Test với tài khoản sandbox

**Thẻ ATM:**
- Số thẻ: `9704198526191432198`
- Tên: `NGUYEN VAN A`
- Ngày phát hành: `07/15`
- OTP: `123456`

**Thẻ quốc tế:**
- Số thẻ: `4111111111111111`
- Tên: `NGUYEN VAN A`
- Ngày hết hạn: `07/15`
- CVV: `123`

## 📚 Xem hướng dẫn chi tiết

Xem file `docs/VNPAY_SETUP.md` để biết thêm chi tiết.

