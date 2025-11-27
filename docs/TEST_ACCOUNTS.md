# 🔐 TÀI KHOẢN TEST

## 👨‍💼 ADMIN & STAFF

### Admin
- **Email:** `admin@cinema.com`
- **Password:** `password`
- **Phone:** 0900000001
- **Role:** Admin
- **Quyền:** Quản trị toàn bộ hệ thống

### Staff (Nhân viên)
- **Email:** `huy@cinema.com`
- **Password:** `password`
- **Phone:** 0901234567
- **Role:** Staff
- **Quyền:** Check-in vé, quản lý đơn hàng

- **Email:** `hai@cinema.com`
- **Password:** `password`
- **Phone:** 0901234568
- **Role:** Staff

- **Email:** `hang@cinema.com`
- **Password:** `password`
- **Phone:** 0901234569
- **Role:** Staff

- **Email:** `hung@cinema.com`
- **Password:** `password`
- **Phone:** 0901234570
- **Role:** Staff

---

## 👤 USERS (Khách hàng)

### User 1 - Nguyễn Văn An
- **Email:** `user1@test.com`
- **Password:** `password`
- **Phone:** 0912345678
- **Member:** ✅ Có

### User 2 - Trần Thị Bình
- **Email:** `user2@test.com`
- **Password:** `password`
- **Phone:** 0912345679
- **Member:** ✅ Có

### User 3 - Lê Văn Cường
- **Email:** `user3@test.com`
- **Password:** `password`
- **Phone:** 0912345680
- **Member:** ❌ Không

### User 4 - Phạm Thị Dung
- **Email:** `user4@test.com`
- **Password:** `password`
- **Phone:** 0912345681
- **Member:** ✅ Có

### User 5 - Hoàng Văn Em
- **Email:** `user5@test.com`
- **Password:** `password`
- **Phone:** 0912345682
- **Member:** ❌ Không

### User 6-20
- **Email:** `user6@test.com` đến `user20@test.com`
- **Password:** `password`
- **Phone:** 0900000083 đến 0900000097
- **Member:** Ngẫu nhiên

---

## 📝 GHI CHÚ

- **Tất cả tài khoản đều dùng password:** `password`
- **Admin và Staff** có quyền truy cập vào admin panel
- **Users** chỉ có quyền đặt vé và xem lịch sử đơn hàng
- **Member** có thể nhận được các ưu đãi đặc biệt

---

## 🚀 CÁCH SỬ DỤNG

### Chạy seeder để tạo tài khoản:

```powershell
# Trong Docker
docker exec cinema_app php artisan db:seed --class=UserSeeder

# Hoặc fresh migration với seed
docker exec cinema_app php artisan migrate:fresh --seed
```

### Đăng nhập:

1. Truy cập: http://localhost:8089/login
2. Nhập email và password từ danh sách trên
3. Click "Đăng nhập"

---

## 🧪 TEST SCENARIOS

### Test Admin:
- Đăng nhập với `admin@cinema.com`
- Truy cập `/admin` để quản lý hệ thống
- CRUD phim, rạp, phòng, suất chiếu
- Quản lý đơn hàng, mã giảm giá
- Xem báo cáo doanh thu

### Test Staff:
- Đăng nhập với `huy@cinema.com` hoặc các staff khác
- Truy cập `/staff` để check-in vé
- Quét QR code từ vé để xác thực

### Test User:
- Đăng nhập với `user1@test.com`
- Xem lịch chiếu, chọn ghế
- Đặt vé và thanh toán
- Xem lịch sử đơn hàng
- Nhận email với PDF vé

---

**Lưu ý:** Đảm bảo đã chạy seeder trước khi test!

