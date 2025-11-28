# Hướng dẫn Deploy lên Railway từ Docker Image

Railway hỗ trợ deploy từ Docker image hoặc từ GitHub repository. Hướng dẫn này sẽ giúp bạn deploy ứng dụng Laravel lên Railway.

## Phương pháp 1: Deploy từ Docker Image (Docker Hub)

### Bước 1: Build và Push Docker Image lên Docker Hub

#### 1.1. Đăng nhập Docker Hub
```bash
docker login
```

#### 1.2. Build Docker image
```bash
# Build image với tag
docker build -f Dockerfile.railway -t huykunptit/cinema-app:latest .

# Hoặc với version tag
docker build -f Dockerfile.railway -t huykunptit/cinema-app:v1.0.0 .
```

#### 1.3. Push image lên Docker Hub
```bash
# Push latest
docker push huykunptit/cinema-app:latest

# Hoặc push version
docker push huykunptit/cinema-app:v1.0.0
```

### Bước 2: Deploy trên Railway

#### 2.1. Tạo Project mới trên Railway
1. Truy cập [Railway](https://railway.com/)
2. Đăng nhập/Đăng ký tài khoản
3. Click "New Project"
4. Chọn "Deploy from Docker Hub"

#### 2.2. Cấu hình Docker Image
- **Image Name**: `huykunptit/cinema-app:latest`
- **Port**: Railway tự động detect, nhưng đảm bảo container expose port 80

#### 2.3. Cấu hình Environment Variables
Thêm các biến môi trường sau trong Railway Dashboard:

**Laravel Core:**
```
APP_NAME=CinemaApp
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

**Database (Railway sẽ tự động tạo PostgreSQL hoặc MySQL):**
```
DB_CONNECTION=mysql
DB_HOST=your-db-host.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-db-password
```

**Redis (nếu cần, tạo Redis service trên Railway):**
```
REDIS_HOST=your-redis-host.railway.internal
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Cache & Session:**
```
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**Mail (SMTP):**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Pusher (nếu dùng real-time notifications):**
```
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_APP_CLUSTER=ap1
```

**Google OAuth (nếu dùng):**
```
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=https://your-app.railway.app/auth/google/callback
```

**SpeedSMS (nếu dùng):**
```
SPEEDSMS_ENABLED=true
SPEEDSMS_API_KEY=your-api-key
SPEEDSMS_SENDER=your-sender-name
```

**Other:**
```
RUN_MIGRATIONS=true
```

### Bước 3: Tạo Database Service

1. Trong Railway project, click "New" → "Database" → "MySQL" (hoặc PostgreSQL)
2. Railway sẽ tự động tạo database và cung cấp connection string
3. Copy các thông tin DB và cập nhật vào Environment Variables

### Bước 4: Tạo Redis Service (nếu cần)

1. Click "New" → "Database" → "Redis"
2. Railway sẽ tự động tạo Redis instance
3. Cập nhật REDIS_HOST trong Environment Variables

### Bước 5: Deploy và Monitor

1. Railway sẽ tự động build và deploy
2. Xem logs trong tab "Deployments" → "View Logs"
3. Sau khi deploy thành công, Railway sẽ cung cấp URL: `https://your-app.railway.app`

## Phương pháp 2: Deploy từ GitHub Repository

### Bước 1: Push code lên GitHub

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

### Bước 2: Connect GitHub với Railway

1. Trên Railway Dashboard, click "New Project"
2. Chọn "Deploy from GitHub repo"
3. Authorize Railway để truy cập GitHub
4. Chọn repository của bạn

### Bước 3: Cấu hình Build Settings

Railway sẽ tự động detect Dockerfile. Nếu bạn muốn dùng `Dockerfile.railway`:

1. Vào "Settings" → "Build"
2. Set **Dockerfile Path**: `Dockerfile.railway`
3. Set **Root Directory**: `/` (hoặc để trống)

### Bước 4: Cấu hình Environment Variables

Giống như Phương pháp 1, thêm tất cả environment variables cần thiết.

### Bước 5: Deploy

Railway sẽ tự động:
- Build Docker image từ GitHub
- Deploy khi có push mới
- Chạy migrations nếu `RUN_MIGRATIONS=true`

## Tùy chỉnh cho Railway

### Custom Domain

1. Vào "Settings" → "Networking"
2. Click "Generate Domain" để có domain mặc định
3. Hoặc "Add Custom Domain" để dùng domain riêng
4. Cập nhật `APP_URL` trong Environment Variables

### Health Check

Railway sẽ tự động check endpoint `/health`. Endpoint này đã được cấu hình trong `nginx.railway.conf`.

### Auto Deploy

Railway tự động deploy khi:
- Push code mới lên GitHub (nếu dùng GitHub)
- Push image mới lên Docker Hub (nếu dùng Docker Hub)

### Scaling

1. Vào "Settings" → "Scaling"
2. Điều chỉnh số replicas nếu cần
3. Railway sẽ tự động scale dựa trên traffic

## Troubleshooting

### Lỗi Database Connection

- Kiểm tra DB_HOST có đúng không (Railway dùng `.railway.internal` cho internal services)
- Đảm bảo DB service đã được tạo và running
- Kiểm tra DB credentials trong Environment Variables

### Lỗi Permission

- Đảm bảo storage và bootstrap/cache có quyền write
- Dockerfile đã set permissions đúng

### Lỗi Build

- Kiểm tra logs trong Railway Dashboard
- Đảm bảo Dockerfile.railway tồn tại và đúng syntax
- Kiểm tra .dockerignore không loại trừ files cần thiết

### Lỗi 502 Bad Gateway

- Kiểm tra nginx và php-fpm đang chạy
- Xem logs: `docker logs <container-id>`
- Kiểm tra health check endpoint

## Lưu ý

1. **Database**: Railway cung cấp MySQL/PostgreSQL miễn phí với giới hạn. Nâng cấp plan nếu cần.
2. **Storage**: Railway không persist storage giữa các deployments. Dùng external storage (S3, etc.) cho files upload.
3. **Environment Variables**: Không commit `.env` file. Dùng Railway's environment variables.
4. **Secrets**: Dùng Railway's secrets management cho sensitive data.
5. **Costs**: Railway có free tier nhưng có giới hạn. Monitor usage trong Dashboard.

## Tài liệu tham khảo

- [Railway Documentation](https://docs.railway.app/)
- [Railway Docker Guide](https://docs.railway.app/deploy/dockerfiles)
- [Laravel Deployment](https://laravel.com/docs/deployment)

