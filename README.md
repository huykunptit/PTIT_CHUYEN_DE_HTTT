# Cinema - Hệ thống đặt vé xem phim

Hệ thống đặt vé xem phim hiện đại được xây dựng với Laravel 11 và Docker.

## Tính năng chính

### Frontend (Giao diện khách hàng)
- **Trang chủ**: Hiển thị phim nổi bật, phim sắp chiếu, banner quảng cáo
- **Danh sách phim**: Tìm kiếm và lọc phim theo thể loại, trạng thái
- **Chi tiết phim**: Thông tin phim, lịch chiếu, đặt vé
- **Đặt vé**: Chọn ghế, thông tin khách hàng, thanh toán
- **Thanh toán**: Tích hợp VNPAY (QR, ATM, thẻ quốc tế)
- **Đăng ký/Đăng nhập**: Quản lý tài khoản khách hàng

### Backend (Admin Panel)
- **Dashboard**: Thống kê doanh thu, số vé bán, khách hàng
- **Quản lý phim**: CRUD phim, thể loại, đánh giá, trạng thái
- **Quản lý rạp**: CRUD rạp chiếu, phòng chiếu, ghế
- **Lịch chiếu**: Tạo lịch chiếu, kiểm tra xung đột thời gian
- **Đặt vé**: Xem danh sách đặt vé, cập nhật trạng thái
- **Thanh toán**: Theo dõi thanh toán, cập nhật trạng thái

## Công nghệ sử dụng

- **Backend**: Laravel 11, PHP 8.2+
- **Database**: MySQL 8.0
- **Cache**: Redis
- **Queue**: Redis
- **Frontend**: Bootstrap 5, Font Awesome
- **Payment**: VNPAY
- **Container**: Docker, Docker Compose

## Cài đặt và chạy

### 1. Clone repository
```bash
git clone <repository-url>
cd cinema
```

### 2. Cấu hình Docker
```bash
# Copy file cấu hình
cp env-template.txt .env

# Chỉnh sửa .env với thông tin của bạn
# Đặc biệt chú ý:
# - APP_KEY
# - DB_DATABASE, DB_USERNAME, DB_PASSWORD
# - VNPAY_TMN_CODE, VNPAY_HASH_SECRET
```

### 3. Chạy Docker
```bash
# Build và chạy containers
docker-compose up -d

# Hoặc sử dụng Makefile
make dev
```

### 4. Cài đặt Laravel
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

# Tạo storage link
php artisan storage:link
```

### 5. Truy cập ứng dụng
- **Frontend**: http://localhost:8089
- **Admin Panel**: http://localhost:8089/admin
- **phpMyAdmin**: http://localhost:8080

## Cấu trúc dự án

```
cinema/
├── app/
│   ├── Http/Controllers/
│   │   ├── Frontend/          # Controllers cho frontend
│   │   ├── Admin/             # Controllers cho admin
│   │   └── Auth/              # Controllers cho authentication
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   └── views/
│       ├── frontend/          # Views cho frontend
│       ├── admin/             # Views cho admin
│       └── layouts/           # Layout templates
├── routes/
│   └── web.php                # Web routes
├── docker-compose.yml         # Docker configuration
├── Dockerfile                 # Docker image configuration
└── Makefile                   # Convenient commands
```

## Các lệnh hữu ích

### Docker Commands
```bash
make dev          # Chạy development
make up           # Khởi động containers
make down         # Dừng containers
make logs         # Xem logs
make shell        # Vào container app
make clean        # Dọn dẹp containers
```

### Laravel Commands
```bash
make migrate-fresh    # Chạy migrations mới
make cache-clear      # Xóa cache
make test            # Chạy tests
```

## Cấu hình VNPAY

1. Đăng ký tài khoản VNPAY
2. Lấy thông tin:
   - TMN Code
   - Hash Secret
3. Cập nhật trong `.env`:
   ```
   VNPAY_TMN_CODE=your_tmn_code
   VNPAY_HASH_SECRET=your_hash_secret
   ```

## Database Schema

### Bảng chính
- **users**: Thông tin người dùng
- **cinemas**: Thông tin rạp chiếu
- **movies**: Thông tin phim
- **rooms**: Phòng chiếu
- **seats**: Ghế ngồi
- **showtimes**: Lịch chiếu
- **bookings**: Đặt vé
- **tickets**: Vé xem phim
- **promotions**: Khuyến mãi

### Relationships
- User hasMany Bookings
- Cinema hasMany Rooms
- Movie hasMany Showtimes
- Room hasMany Seats, Showtimes
- Showtime belongsTo Movie, Room
- Booking belongsTo User, Showtime
- Ticket belongsTo Booking, Seat

## Tính năng nâng cao

### 1. Hệ thống ghế
- Ghế thường, VIP, đôi
- Giá vé khác nhau theo loại ghế
- Kiểm tra ghế đã bán

### 2. Lịch chiếu
- Kiểm tra xung đột thời gian
- Tự động tính thời gian kết thúc
- Quản lý trạng thái chiếu

### 3. Thanh toán
- Tích hợp VNPAY
- Hỗ trợ QR, ATM, thẻ quốc tế
- Đếm ngược thời gian giữ vé

### 4. Khuyến mãi
- Mã giảm giá
- Giảm giá theo phần trăm/số tiền
- Giới hạn sử dụng

## Troubleshooting

### 1. Lỗi database connection
```bash
# Kiểm tra MySQL container
docker-compose logs mysql

# Restart MySQL
docker-compose restart mysql
```

### 2. Lỗi permissions
```bash
# Fix permissions
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### 3. Lỗi cache
```bash
# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
```

## Contributing

1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push to branch
5. Tạo Pull Request

## License

MIT License

## Support

Nếu gặp vấn đề, vui lòng tạo issue hoặc liên hệ qua email.

---

**Cinema - Hệ thống đặt vé xem phim hiện đại** 🎬