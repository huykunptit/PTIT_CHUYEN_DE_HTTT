# 📋 Hướng Dẫn Tạo File .env cho Docker

## ✅ File .env đã được tạo!

Tôi đã tạo file `.env` với cấu hình phù hợp cho Docker setup của bạn.

## 🔧 Cấu hình chính:

```env
APP_NAME=Cinemat
APP_ENV=local
APP_KEY=base64:your-app-key-here  # Đã được generate tự động
APP_DEBUG=true
APP_URL=http://localhost:8089

# Database (Docker)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=cinemat
DB_USERNAME=cinemat
DB_PASSWORD=secret

# Cache & Sessions (Redis)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=redis
```

## 🚀 Bước tiếp theo:

### 1. Kiểm tra file .env
```bash
# Xem nội dung file .env
type .env

# Hoặc mở bằng editor
notepad .env
```

### 2. Khởi động ứng dụng
```bash
# Sử dụng Makefile
make dev

# Hoặc Docker Compose
docker-compose up -d
```

### 3. Chạy migrations
```bash
make migrate-fresh
# hoặc
docker-compose exec app php artisan migrate:fresh --seed
```

### 4. Truy cập ứng dụng
- **Website**: http://localhost:8089
- **phpMyAdmin**: http://localhost:8081

## 🔍 Kiểm tra cấu hình:

```bash
# Kiểm tra APP_KEY
docker-compose exec app php artisan key:generate --show

# Kiểm tra database connection
docker-compose exec app php artisan tinker
# Trong tinker: DB::connection()->getPdo();

# Kiểm tra Redis
docker-compose exec app php artisan tinker
# Trong tinker: Redis::ping();
```

## 📝 Lưu ý:

- ✅ APP_KEY đã được generate tự động
- ✅ Database credentials phù hợp với Docker
- ✅ Redis configuration đã được thiết lập
- ✅ Port 8089 cho website
- ✅ Port 8081 cho phpMyAdmin

## 🛠️ Troubleshooting:

Nếu gặp lỗi:

1. **Permission issues:**
   ```bash
   make shell-root
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Database connection:**
   ```bash
   docker-compose logs mysql
   ```

3. **Clear cache:**
   ```bash
   make cache-clear
   ```

**Bây giờ bạn có thể bắt đầu development! 🎉**
