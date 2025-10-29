# Cinema - Hướng dẫn nhanh

## 🚀 Chạy hệ thống

### 1. Khởi động Docker
```bash
# Chạy tất cả services
docker-compose up -d

# Hoặc sử dụng Makefile
make dev
```

### 2. Cài đặt Laravel
```bash
# Vào container app
docker-compose exec app bash

# Cài đặt dependencies
composer install

# Tạo key
php artisan key:generate

# Chạy migrations
php artisan migrate

# Chạy seeders
php artisan db:seed
```

### 3. Truy cập ứng dụng
- **Frontend**: http://localhost:8089
- **Admin Panel**: http://localhost:8089/admin
- **phpMyAdmin**: http://localhost:8080

## 📱 Tính năng chính

### Frontend
- ✅ Trang chủ với phim nổi bật
- ✅ Danh sách phim với bộ lọc
- ✅ Chi tiết phim và lịch chiếu
- ✅ Đặt vé với chọn ghế
- ✅ Thanh toán VNPAY
- ✅ Đăng ký/Đăng nhập

### Admin Panel
- ✅ Dashboard thống kê
- ✅ Quản lý phim (CRUD)
- ✅ Quản lý rạp (CRUD)
- ✅ Quản lý lịch chiếu
- ✅ Quản lý đặt vé
- ✅ Cập nhật trạng thái thanh toán

## 🛠️ Các lệnh hữu ích

```bash
# Docker
make up           # Khởi động
make down         # Dừng
make logs         # Xem logs
make shell        # Vào container

# Laravel
make migrate-fresh    # Chạy migrations mới
make cache-clear      # Xóa cache
make test            # Chạy tests
```

## 🔧 Cấu hình VNPAY

1. Đăng ký tài khoản VNPAY
2. Lấy thông tin:
   - TMN Code
   - Hash Secret
3. Cập nhật trong `.env`:
   ```
   VNPAY_TMN_CODE=your_tmn_code
   VNPAY_HASH_SECRET=your_hash_secret
   ```

## 📊 Database

### Bảng chính
- **users**: Người dùng
- **cinemas**: Rạp chiếu
- **movies**: Phim
- **rooms**: Phòng chiếu
- **seats**: Ghế
- **showtimes**: Lịch chiếu
- **bookings**: Đặt vé
- **tickets**: Vé

### Sample Data
- 2 rạp chiếu
- 3 phim mẫu
- Lịch chiếu mẫu
- Ghế mẫu

## 🎯 Workflow

### 1. Khách hàng
1. Xem phim trên trang chủ
2. Chọn phim và lịch chiếu
3. Chọn ghế và đặt vé
4. Thanh toán qua VNPAY
5. Nhận vé

### 2. Admin
1. Đăng nhập admin panel
2. Quản lý phim, rạp, lịch chiếu
3. Theo dõi đặt vé
4. Cập nhật trạng thái thanh toán

## 🐛 Troubleshooting

### Lỗi database
```bash
docker-compose restart mysql
```

### Lỗi permissions
```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Lỗi cache
```bash
make cache-clear
```

## 📝 Notes

- Hệ thống sử dụng Redis cho cache và session
- VNPAY cần cấu hình đúng để thanh toán
- Database sẽ được tạo tự động khi chạy migrations
- Sample data sẽ được tạo khi chạy seeders

---

**Cinema - Hệ thống đặt vé xem phim** 🎬