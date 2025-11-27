# 🪟 Hướng dẫn chạy trên Windows

## 📋 Yêu cầu

- Docker Desktop for Windows
- Git Bash hoặc PowerShell
- Ports: 8089, 3308, 6379, 8081 (phpMyAdmin)

---

## 🚀 CÁC LỆNH CẦN CHẠY

### 1. Khởi động Docker Desktop
Đảm bảo Docker Desktop đang chạy trước khi thực hiện các lệnh sau.

### 2. Build và khởi động containers

```powershell
# Di chuyển vào thư mục project
cd D:\Cinemat

# Build images
docker-compose build

# Khởi động tất cả services
docker-compose up -d

# Kiểm tra status
docker-compose ps
```

### 3. Cài đặt dependencies

```powershell
# Cài đặt Composer packages
docker exec cinema_app composer install

# Cài đặt NPM packages
docker exec cinema_app npm install

# Build assets
docker exec cinema_app npm run build
```

### 4. Cấu hình Laravel

```powershell
# Tạo .env file (nếu chưa có)
docker exec cinema_app cp .env.example .env

# Generate application key
docker exec cinema_app php artisan key:generate

# Tạo storage link
docker exec cinema_app php artisan storage:link

# Clear cache
docker exec cinema_app php artisan config:clear
docker exec cinema_app php artisan cache:clear
docker exec cinema_app php artisan route:clear
docker exec cinema_app php artisan view:clear
```

### 5. Database setup

```powershell
# Chạy migrations
docker exec cinema_app php artisan migrate

# Chạy seeders (nếu có)
docker exec cinema_app php artisan db:seed

# Hoặc fresh migration với seed
docker exec cinema_app php artisan migrate:fresh --seed
```

### 6. Kiểm tra services

```powershell
# Xem logs của tất cả services
docker-compose logs -f

# Xem logs của từng service
docker-compose logs -f app
docker-compose logs -f queue
docker-compose logs -f scheduler
docker-compose logs -f redis
docker-compose logs -f mysql

# Kiểm tra status
docker-compose ps
```

---

## 🔧 CÁC LỆNH THƯỜNG DÙNG

### Quản lý containers

```powershell
# Khởi động
docker-compose up -d

# Dừng
docker-compose down

# Restart
docker-compose restart

# Restart một service cụ thể
docker-compose restart app
docker-compose restart queue
docker-compose restart scheduler

# Xem logs realtime
docker-compose logs -f [service_name]

# Vào shell của container
docker exec -it cinema_app sh
docker exec -it cinema_queue sh
docker exec -it cinema_scheduler sh
```

### Laravel commands

```powershell
# Artisan commands
docker exec cinema_app php artisan [command]

# Ví dụ:
docker exec cinema_app php artisan migrate
docker exec cinema_app php artisan cache:clear
docker exec cinema_app php artisan queue:work
docker exec cinema_app php artisan schedule:run
docker exec cinema_app php artisan tinker
```

### Composer & NPM

```powershell
# Composer
docker exec cinema_app composer install
docker exec cinema_app composer update
docker exec cinema_app composer require [package]

# NPM
docker exec cinema_app npm install
docker exec cinema_app npm run dev
docker exec cinema_app npm run build
```

### Database

```powershell
# Migrations
docker exec cinema_app php artisan migrate
docker exec cinema_app php artisan migrate:fresh
docker exec cinema_app php artisan migrate:fresh --seed

# Seeders
docker exec cinema_app php artisan db:seed

# Tinker (test database)
docker exec -it cinema_app php artisan tinker
```

### Cache & Optimization

```powershell
# Clear all caches
docker exec cinema_app php artisan optimize:clear

# Cache config
docker exec cinema_app php artisan config:cache
docker exec cinema_app php artisan route:cache
docker exec cinema_app php artisan view:cache
```

---

## 🧪 TEST CÁC TÍNH NĂNG

### 1. Test Queue Worker

```powershell
# Kiểm tra queue worker đang chạy
docker-compose logs queue

# Test queue bằng cách tạo booking và thanh toán
# Queue sẽ xử lý email notifications
```

### 2. Test Scheduler

```powershell
# Kiểm tra scheduler đang chạy
docker-compose logs scheduler

# Chạy thủ công để test
docker exec cinema_scheduler php artisan schedule:run --verbose

# Test cleanup expired holds
docker exec cinema_app php artisan seats:cleanup-expired-holds

# Test expire bookings
docker exec cinema_app php artisan bookings:expire
```

### 3. Test Redis

```powershell
# Vào Redis CLI
docker exec -it cinema_redis redis-cli

# Trong Redis CLI:
# PING (kiểm tra kết nối)
# KEYS * (xem tất cả keys)
# GET seat_hold:1:1 (kiểm tra seat hold)
```

### 4. Test PDF Generation

```powershell
# Tạo booking và thanh toán thành công
# Kiểm tra email có PDF attachment
# Hoặc test trong tinker:
docker exec -it cinema_app php artisan tinker

# Trong tinker:
$ticket = App\Models\Ticket::first();
$mail = new App\Mail\TicketPdfMail($ticket);
Mail::to('test@example.com')->send($mail);
```

---

## 🐛 TROUBLESHOOTING

### Lỗi kết nối database

```powershell
# Kiểm tra MySQL đang chạy
docker-compose ps mysql

# Xem logs MySQL
docker-compose logs mysql

# Restart MySQL
docker-compose restart mysql
```

### Lỗi Redis connection

```powershell
# Kiểm tra Redis đang chạy
docker-compose ps redis

# Test kết nối Redis
docker exec cinema_app php artisan tinker
# Trong tinker: Redis::ping();
```

### Queue không chạy

```powershell
# Kiểm tra queue container
docker-compose ps queue

# Xem logs queue
docker-compose logs queue

# Restart queue
docker-compose restart queue

# Chạy queue thủ công để test
docker exec cinema_queue php artisan queue:work
```

### Scheduler không chạy

```powershell
# Kiểm tra scheduler container
docker-compose ps scheduler

# Xem logs scheduler
docker-compose logs scheduler

# Restart scheduler
docker-compose restart scheduler

# Test chạy thủ công
docker exec cinema_scheduler php artisan schedule:run --verbose
```

### Permission errors

```powershell
# Fix permissions
docker exec --user root cinema_app chmod -R 775 storage bootstrap/cache
docker exec --user root cinema_app chown -R www-data:www-data storage bootstrap/cache
```

### Clear everything và start fresh

```powershell
# Dừng và xóa tất cả
docker-compose down -v

# Xóa images (nếu cần)
docker-compose down --rmi all

# Build lại
docker-compose build --no-cache

# Khởi động lại
docker-compose up -d
```

---

## 📊 MONITORING

### Xem resource usage

```powershell
# Xem CPU, Memory của containers
docker stats

# Xem disk usage
docker system df
```

### Xem logs

```powershell
# Tất cả services
docker-compose logs -f

# Một service cụ thể
docker-compose logs -f app
docker-compose logs -f queue
docker-compose logs -f scheduler

# Last 100 lines
docker-compose logs --tail=100 app
```

---

## 🔄 WORKFLOW PHÁT TRIỂN

### 1. Lần đầu setup

```powershell
# 1. Clone project
git clone [repo-url]
cd Cinemat

# 2. Copy .env
copy .env.example .env

# 3. Build và start
docker-compose build
docker-compose up -d

# 4. Install dependencies
docker exec cinema_app composer install
docker exec cinema_app npm install
docker exec cinema_app npm run build

# 5. Setup Laravel
docker exec cinema_app php artisan key:generate
docker exec cinema_app php artisan storage:link
docker exec cinema_app php artisan migrate --seed

# 6. Clear cache
docker exec cinema_app php artisan optimize:clear
```

### 2. Mỗi lần code mới

```powershell
# Pull code mới
git pull

# Update dependencies (nếu có)
docker exec cinema_app composer install
docker exec cinema_app npm install
docker exec cinema_app npm run build

# Run migrations (nếu có)
docker exec cinema_app php artisan migrate

# Clear cache
docker exec cinema_app php artisan optimize:clear

# Restart services (nếu cần)
docker-compose restart
```

### 3. Trước khi commit

```powershell
# Clear cache
docker exec cinema_app php artisan optimize:clear

# Run tests (nếu có)
docker exec cinema_app php artisan test

# Check code style (nếu có)
docker exec cinema_app ./vendor/bin/pint
```

---

## 🚢 CHUẨN BỊ BUILD TRÊN GITHUB

### 1. Đảm bảo code sạch

```powershell
# Clear cache
docker exec cinema_app php artisan optimize:clear

# Remove node_modules và vendor từ .gitignore (nếu cần)
# Đảm bảo .env không được commit
```

### 2. Test tất cả tính năng

```powershell
# Test queue
docker-compose logs queue

# Test scheduler
docker-compose logs scheduler

# Test Redis
docker exec -it cinema_redis redis-cli PING

# Test database
docker exec cinema_app php artisan migrate:status
```

### 3. Commit và push

```powershell
git add .
git commit -m "feat: implement all required features"
git push origin main
```

---

## 📝 NOTES

- **Ports đang sử dụng:**
  - 8089: Nginx (Web)
  - 3308: MySQL
  - 6379: Redis
  - 8081: phpMyAdmin
  - 9000: PHP-FPM

- **Services chạy:**
  - `cinema_app`: PHP-FPM application
  - `cinema_nginx`: Nginx web server
  - `cinema_mysql`: MySQL database
  - `cinema_redis`: Redis cache/queue
  - `cinema_queue`: Queue worker
  - `cinema_scheduler`: Scheduled tasks
  - `cinema_phpmyadmin`: phpMyAdmin

- **Environment variables quan trọng:**
  - `QUEUE_CONNECTION=redis`
  - `CACHE_DRIVER=redis`
  - `SESSION_DRIVER=redis`
  - `REDIS_HOST=redis`

---

**Lưu ý:** Tất cả lệnh trên chạy trong PowerShell hoặc Git Bash trên Windows.

