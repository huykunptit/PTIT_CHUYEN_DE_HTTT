# Hướng dẫn Deploy lên Heroku sử dụng Container Registry

## Tên App: `appdemoheroku3`

---

## **Bước 1: Chuẩn bị môi trường**

### 1.1 Cài đặt Heroku CLI (nếu chưa có)
```powershell
# Windows - sử dụng Chocolatey
choco install heroku-cli

# Hoặc tải từ: https://devcenter.heroku.com/articles/heroku-cli
```

### 1.2 Kiểm tra Docker đang chạy
```powershell
docker --version
docker ps
```

### 1.3 Kiểm tra Heroku CLI
```powershell
heroku --version
heroku login
```

---

## **Bước 2: Đăng nhập vào Heroku Container Registry**

```powershell
# Đăng nhập vào Heroku Container Registry
heroku container:login
```

---

## **Bước 3: Build Docker Image cho Heroku**

```powershell
# Di chuyển vào thư mục project
cd d:\Cinemat

# Build image với tên Heroku app
heroku container:push web -a appdemoheroku3
```

**Giải thích:**
- `web` = dyno type (process type được định nghĩa trong Procfile)
- `-a appdemoheroku3` = tên app trên Heroku
- Tự động sử dụng `Dockerfile` trong project

---

## **Bước 4: Release lên Heroku**

```powershell
# Deploy image đã push lên
heroku container:release web -a appdemoheroku3
```

---

## **Bước 5: Kiểm tra Logs**

```powershell
# Xem logs từ ứng dụng
heroku logs --tail -a appdemoheroku3

# Hoặc
heroku logs -a appdemoheroku3 --num 100
```

---

## **Bước 6: Chạy Database Migrations (nếu cần)**

```powershell
# Run migrations
heroku run "php artisan migrate --force" -a appdemoheroku3

# Run seeds (nếu cần)
heroku run "php artisan db:seed --force" -a appdemoheroku3
```

---

## **Quick Deploy Script (PowerShell)**

Tạo file `deploy-heroku.ps1` với nội dung:

```powershell
param(
    [string]$AppName = "appdemoheroku3",
    [switch]$WithMigration = $false
)

$ErrorActionPreference = "Stop"

Write-Host "🚀 Starting Heroku Container Registry Deployment..." -ForegroundColor Green
Write-Host "App: $AppName" -ForegroundColor Yellow

# Step 1: Login to Heroku Container Registry
Write-Host "`n1️⃣ Logging into Heroku Container Registry..." -ForegroundColor Cyan
heroku container:login

# Step 2: Build and Push
Write-Host "`n2️⃣ Building and pushing Docker image..." -ForegroundColor Cyan
heroku container:push web -a $AppName

# Step 3: Release
Write-Host "`n3️⃣ Releasing container..." -ForegroundColor Cyan
heroku container:release web -a $AppName

# Step 4: Check logs
Write-Host "`n4️⃣ Checking deployment logs (last 50 lines)..." -ForegroundColor Cyan
heroku logs -n 50 -a $AppName

# Step 5: Optional - Run migrations
if ($WithMigration) {
    Write-Host "`n5️⃣ Running database migrations..." -ForegroundColor Cyan
    heroku run "php artisan migrate --force" -a $AppName
}

Write-Host "`n✅ Deployment completed!" -ForegroundColor Green
Write-Host "Access your app at: https://$AppName.herokuapp.com" -ForegroundColor Green
```

**Cách sử dụng:**
```powershell
# Chạy deployment đơn giản
.\deploy-heroku.ps1

# Chạy với migrations
.\deploy-heroku.ps1 -WithMigration
```

---

## **Các Lệnh Hữu ích**

```powershell
# Xem thông tin app
heroku apps:info -a appdemoheroku3

# Xem environment variables
heroku config -a appdemoheroku3

# Set environment variable
heroku config:set APP_DEBUG=false -a appdemoheroku3

# Xem dyno logs (real-time)
heroku logs --tail -a appdemoheroku3

# Restart app
heroku restart -a appdemoheroku3

# Xem containers đã push
heroku container:ls -a appdemoheroku3
```

---

## **Troubleshooting**

### ❌ Lỗi: "Error: docker credentials not found"
```powershell
# Xoá và đăng nhập lại
heroku container:logout
heroku container:login
```

### ❌ Lỗi: "App not found"
```powershell
# Kiểm tra tên app có chính xác không
heroku apps -a appdemoheroku3
```

### ❌ App crash sau khi deploy
```powershell
# Kiểm tra logs
heroku logs -a appdemoheroku3

# Check Procfile syntax
# Procfile hiện tại:
# web: heroku-php-apache2 public/
# worker: php artisan queue:work --sleep=3 --tries=3 --timeout=90
```

### ❌ Database connection error
```powershell
# Kiểm tra DATABASE_URL đã được set chưa
heroku config -a appdemoheroku3 | grep DATABASE_URL

# Hoặc set manual
heroku config:set DATABASE_URL="mysql://user:pass@host:port/db" -a appdemoheroku3
```

---

## **Dockerfile Optimization Notes**

Dockerfile hiện tại:
- ✅ Sử dụng PHP 8.2 Alpine (lightweight)
- ✅ Cài đặt dependencies cần thiết
- ✅ Composer install với --no-dev (production-ready)

**Có thể cải thiện thêm:**
```dockerfile
# Thêm health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port 80 cho web
EXPOSE 80
```

---

## **Checklist Pre-Deployment**

- [ ] Environment variables configured (`.env` hoặc Heroku config)
- [ ] Database URL/credentials set
- [ ] Storage permissions configured
- [ ] Cache driver configured (Redis nếu cần)
- [ ] Mail driver configured
- [ ] APP_KEY set
- [ ] APP_DEBUG = false cho production
- [ ] Dockerfile không có issues
- [ ] Docker build thành công local

---

## **Notes**

- Heroku free tier has been discontinued. App sẽ cần plan trả phí.
- Build mất khoảng 3-5 phút tuỳ theo kích thước
- Container Registry dùng Docker technology, khác với Git deployment
- Mỗi deploy push lại toàn bộ Docker image

