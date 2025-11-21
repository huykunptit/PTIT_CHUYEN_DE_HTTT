# SpeedSMS OTP Setup

## 1. Đăng ký SpeedSMS
1. Tạo tài khoản tại https://speedsms.vn
2. Nạp tiền và đăng ký brandname (nếu muốn dùng SMS Brandname)
3. Vào phần **API** để lấy `API Key`

## 2. Cấu hình `.env`
```env
SPEEDSMS_ENABLED=true
SPEEDSMS_API_KEY=your-speedsms-api-key
SPEEDSMS_SENDER=YourBrandName        # để trống nếu dùng SMS CSKH mặc định
SPEEDSMS_TYPE=2                      # 1: QC, 2: CSKH, 3: Brandname...
```

> **Lưu ý:**  
> - `SPEEDSMS_TYPE=2` là SMS chăm sóc khách hàng (không cần brandname).  
> - Nếu dùng brandname, đặt `SPEEDSMS_TYPE=3` và điền `SPEEDSMS_SENDER` đúng tên đã đăng ký.

## 3. Queue worker (fallback email vẫn dùng queue)
```bash
php artisan queue:work
```

## 4. Cách hoạt động
- Khi người dùng yêu cầu đăng nhập bằng số điện thoại, hệ thống gửi OTP qua SpeedSMS.  
- Nếu SpeedSMS gửi thất bại, hệ thống sẽ fallback sang gửi OTP qua email như trước.  
- Định dạng số điện thoại được chuẩn hóa: bắt đầu bằng `+84` (tự động chuyển từ `0xxxxxxxxx`).

## 5. Kiểm thử
1. Bật log SpeedSMS trong dashboard để kiểm tra tin gửi.  
2. Trên môi trường dev, có thể để `SPEEDSMS_ENABLED=false` để chỉ gửi email OTP.  
3. Kiểm tra file log `storage/logs/laravel.log` nếu thấy lỗi từ SpeedSMS API.


