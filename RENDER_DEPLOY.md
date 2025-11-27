# 🚀 Hướng Dẫn Deploy Laravel Cinemat lên Render

## 📋 Tổng Quan

Hướng dẫn này sẽ giúp bạn deploy ứng dụng Laravel Cinemat lên Render sử dụng Docker.

## ✅ Các File Đã Chuẩn Bị

1. **Dockerfile.production** - Dockerfile tối ưu cho production
2. **.dockerignore** - Loại trừ các file không cần thiết khi build
3. **render.yaml** - Cấu hình tự động cho Render (tùy chọn)
4. **docker/supervisor/supervisord.conf** - Quản lý Nginx và PHP-FPM
5. **docker/scripts/start.sh** - Script khởi động ứng dụng
6. **docker/nginx/nginx.production.conf** - Cấu hình Nginx cho production

## 🔧 Bước 1: Chuẩn Bị Repository

1. **Commit và push code lên Git repository:**
   ```bash
   git add .
   git commit -m "Prepare for Render deployment"
   git push origin main
   ```

2. **Đảm bảo repository của bạn có thể truy cập được** (GitHub, GitLab, hoặc Bitbucket)

## 🗄️ Bước 2: Tạo Database trên Render

1. Đăng nhập vào [Render Dashboard](https://dashboard.render.com)
2. Click **"New +"** → **"PostgreSQL"** (hoặc MySQL nếu có)
3. Cấu hình:
   - **Name**: `cinemat-db`
   - **Database**: `cinemat`
   - **User**: `cinemat`
   - **Region**: Singapore (hoặc gần nhất)
   - **Plan**: Starter (hoặc cao hơn)
4. Lưu lại thông tin kết nối:
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`

## 🔴 Bước 3: Tạo Redis trên Render (Tùy chọn)

1. Click **"New +"** → **"Redis"**
2. Cấu hình:
   - **Name**: `cinemat-redis`
   - **Region**: Singapore
   - **Plan**: Starter
3. Lưu lại thông tin:
   - `REDIS_HOST`
   - `REDIS_PORT`
   - `REDIS_PASSWORD` (nếu có)

**Lưu ý:** Nếu không dùng Redis, bạn có thể:
- Sử dụng external Redis service (Upstash, Redis Cloud, etc.)
- Hoặc thay đổi `CACHE_DRIVER=file` và `SESSION_DRIVER=file` trong environment variables

## 🌐 Bước 4: Tạo Web Service

1. Click **"New +"** → **"Web Service"**
2. Kết nối repository của bạn
3. Cấu hình:
   - **Name**: `cinemat-web`
   - **Region**: Singapore
   - **Branch**: `main` (hoặc branch bạn muốn deploy)
   - **Root Directory**: (để trống)
   - **Environment**: **Docker**
   - **Dockerfile Path**: `Dockerfile.production`
   - **Docker Context**: `.` (hoặc để trống)
   - **Plan**: Starter (hoặc cao hơn)

## ⚙️ Bước 5: Cấu Hình Environment Variables

Trong phần **Environment** của Web Service, thêm các biến sau:

### Biến Bắt Buộc

```env
APP_NAME=Cinemat
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

# Database (từ PostgreSQL service đã tạo)
DB_CONNECTION=pgsql
DB_HOST=<từ PostgreSQL service>
DB_PORT=5432
DB_DATABASE=<từ PostgreSQL service>
DB_USERNAME=<từ PostgreSQL service>
DB_PASSWORD=<từ PostgreSQL service>

# Redis (nếu sử dụng)
REDIS_HOST=<từ Redis service>
REDIS_PORT=6379
REDIS_PASSWORD=<từ Redis service hoặc null>

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_LIFETIME=120

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info
```

### Biến Quan Trọng Khác

```env
# Application Key (generate mới)
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Broadcasting
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_APP_CLUSTER=mt1

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@cinemat.com
MAIL_FROM_NAME="${APP_NAME}"

# VNPay Configuration
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_TMN_CODE=your-tmn-code
VNPAY_HASH_SECRET=your-hash-secret
VNPAY_RETURN_URL=https://your-app-name.onrender.com/payment/vnpay/return
VNPAY_IPN_URL=https://your-app-name.onrender.com/payment/vnpay/ipn

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=https://your-app-name.onrender.com/auth/google/callback

# SpeedSMS
SPEEDSMS_ENABLED=false
SPEEDSMS_API_KEY=your-speedsms-api-key

# Movie API
TMDB_API_KEY=your-tmdb-api-key
```

### Generate APP_KEY

Bạn có thể generate APP_KEY bằng cách:

1. Chạy local: `php artisan key:generate --show`
2. Hoặc để script tự động generate khi deploy (đã có trong start.sh)

## 🔄 Bước 6: Deploy

1. Click **"Create Web Service"**
2. Render sẽ tự động build và deploy
3. Theo dõi logs để kiểm tra quá trình build

## 🛠️ Bước 7: Chạy Migrations

Sau khi deploy thành công, migrations sẽ tự động chạy thông qua `start.sh`. 

Nếu cần chạy thủ công:

1. Vào **Shell** trong Render dashboard
2. Chạy:
   ```bash
   php artisan migrate --force
   php artisan db:seed  # Nếu cần seed data
   ```

## 📝 Bước 8: Cấu Hình Queue Worker (Tùy chọn)

Nếu bạn cần chạy queue worker riêng:

1. Tạo **Background Worker** service mới
2. Cấu hình:
   - **Name**: `cinemat-queue`
   - **Environment**: Docker
   - **Dockerfile Path**: `Dockerfile.production`
   - **Command**: `php artisan queue:work redis --sleep=3 --tries=3 --timeout=90`
   - Copy tất cả environment variables từ Web Service

## ⏰ Bước 9: Cấu Hình Scheduler (Tùy chọn)

Để chạy Laravel scheduler, tạo thêm Background Worker:

1. Tạo **Background Worker** service
2. **Name**: `cinemat-scheduler`
3. **Command**: `sh -c "while :; do php artisan schedule:run --verbose --no-interaction & sleep 60; done"`
4. Copy environment variables từ Web Service

## 🔍 Troubleshooting

### 1. Build Failed

- Kiểm tra logs trong Render dashboard
- Đảm bảo `Dockerfile.production` tồn tại
- Kiểm tra `.dockerignore` không loại trừ file cần thiết

### 2. Database Connection Error

- Kiểm tra database service đã running
- Kiểm tra environment variables DB_* đã đúng
- Đảm bảo database service và web service cùng region

### 3. Permission Errors

- Script `start.sh` đã tự động set permissions
- Nếu vẫn lỗi, kiểm tra logs để xem chi tiết

### 4. APP_KEY Missing

- Script sẽ tự động generate nếu chưa có
- Hoặc set thủ công trong environment variables

### 5. Static Files Not Loading

- Đảm bảo đã chạy `php artisan storage:link`
- Kiểm tra permissions của storage folder
- Script `start.sh` đã tự động tạo link

## 📊 Monitoring

- Xem logs real-time trong Render dashboard
- Kiểm tra health check endpoint: `https://your-app.onrender.com/`
- Monitor database và Redis usage

## 🔐 Security Checklist

- [ ] `APP_DEBUG=false` trong production
- [ ] `APP_ENV=production`
- [ ] Sử dụng HTTPS (Render tự động cung cấp)
- [ ] Database password mạnh
- [ ] Redis password (nếu có)
- [ ] Không commit `.env` file
- [ ] Cập nhật `APP_URL` với domain thực tế

## 💰 Cost Optimization

- Sử dụng Starter plan cho development/testing
- Upgrade lên Standard khi cần performance tốt hơn
- Monitor usage để tránh vượt quota
- Sử dụng external Redis nếu Render Redis đắt

## 🔄 Continuous Deployment

Render tự động deploy khi có commit mới lên branch đã cấu hình. Để tắt:

1. Vào **Settings** của service
2. Tắt **Auto-Deploy**

## 📚 Tài Liệu Tham Khảo

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

## 🆘 Hỗ Trợ

Nếu gặp vấn đề:
1. Kiểm tra logs trong Render dashboard
2. Kiểm tra environment variables
3. Test Dockerfile local: `docker build -f Dockerfile.production -t cinemat .`
4. Xem thêm trong file `DOCKER_README.md`

