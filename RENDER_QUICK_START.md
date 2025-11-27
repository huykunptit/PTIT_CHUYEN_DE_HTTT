# ⚡ Quick Start - Deploy lên Render

## 📦 Các File Đã Tạo

✅ **Dockerfile.production** - Docker image cho production  
✅ **.dockerignore** - Tối ưu build process  
✅ **render.yaml** - Cấu hình tự động (tùy chọn)  
✅ **docker/supervisor/supervisord.conf** - Quản lý services  
✅ **docker/scripts/start.sh** - Script khởi động  
✅ **docker/nginx/nginx.production.conf** - Nginx config  
✅ **RENDER_DEPLOY.md** - Hướng dẫn chi tiết  

## 🚀 3 Bước Deploy Nhanh

### 1. Push Code lên Git
```bash
git add .
git commit -m "Add Render deployment files"
git push origin main
```

### 2. Tạo Services trên Render

**Database:**
- New → PostgreSQL
- Name: `cinemat-db`
- Lưu connection info

**Redis (tùy chọn):**
- New → Redis  
- Name: `cinemat-redis`
- Lưu connection info

**Web Service:**
- New → Web Service
- Connect repository
- Environment: **Docker**
- Dockerfile Path: `Dockerfile.production`

### 3. Cấu Hình Environment Variables

Thêm các biến trong Render dashboard:

**Bắt buộc:**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.onrender.com
DB_CONNECTION=pgsql (hoặc mysql)
DB_HOST=<từ PostgreSQL service>
DB_DATABASE=<từ PostgreSQL service>
DB_USERNAME=<từ PostgreSQL service>
DB_PASSWORD=<từ PostgreSQL service>
```

**Nếu dùng Redis:**
```
REDIS_HOST=<từ Redis service>
REDIS_PORT=6379
REDIS_PASSWORD=<từ Redis service>
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**APP_KEY sẽ tự động generate** hoặc set thủ công:
```
APP_KEY=base64:YOUR_KEY_HERE
```

## 📝 Checklist

- [ ] Code đã push lên Git
- [ ] Database service đã tạo
- [ ] Redis service đã tạo (nếu cần)
- [ ] Web service đã tạo với Docker
- [ ] Environment variables đã cấu hình
- [ ] Deploy thành công
- [ ] Migrations đã chạy (tự động hoặc thủ công)
- [ ] Website hoạt động

## 🔍 Kiểm Tra

1. Xem logs: Render Dashboard → Logs
2. Test website: `https://your-app.onrender.com`
3. Chạy migrations (nếu cần): Shell → `php artisan migrate --force`

## 📚 Xem Chi Tiết

Đọc file **RENDER_DEPLOY.md** để biết hướng dẫn đầy đủ.

## ⚠️ Lưu Ý

- Render free tier có thể sleep sau 15 phút không dùng
- Database free tier có giới hạn
- Đảm bảo `APP_DEBUG=false` trong production
- Cập nhật `APP_URL` với domain thực tế

