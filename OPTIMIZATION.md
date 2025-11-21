# Tối ưu Performance - Checklist

## ✅ Đã thực hiện

### 1. Database Optimization
- ✅ Thêm indexes cho các bảng quan trọng (movies, bookings, showtimes, promotions)
- ✅ Sử dụng eager loading để tránh N+1 queries
- ✅ Chỉ select các cột cần thiết thay vì select *

### 2. Caching
- ✅ Cache trang chủ (15 phút)
- ✅ Cache danh sách cinemas (1 giờ)
- ✅ Cache showtimes của phim (30 phút)
- ✅ Cache seats của showtime (5 phút)
- ✅ CacheServiceProvider để tự động clear cache khi có thay đổi

### 3. Code Optimization
- ✅ Tối ưu queries - chỉ select các trường cần thiết
- ✅ Sử dụng select() để giảm memory usage
- ✅ Loại bỏ các query không cần thiết

### 4. Production Configuration
- ✅ Script deploy tự động (deploy.sh)
- ✅ Hướng dẫn deploy chi tiết (DEPLOY.md)
- ✅ Cấu hình .htaccess cho Apache
- ✅ Migration để thêm indexes

## 📋 Checklist trước khi deploy

### Trên Server Ubuntu

- [ ] Cài đặt PHP 8.1+ với tất cả extensions cần thiết
- [ ] Cài đặt MySQL/MariaDB
- [ ] Cài đặt Nginx hoặc Apache
- [ ] Cài đặt Composer
- [ ] Cài đặt Node.js và NPM
- [ ] Cài đặt Redis (khuyến nghị)
- [ ] Cấu hình SSL (Let's Encrypt)

### Cấu hình .env

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-domain.com`
- [ ] Cấu hình database
- [ ] Cấu hình cache driver (Redis khuyến nghị)
- [ ] Cấu hình VNPay credentials
- [ ] Cấu hình SePay credentials

### Sau khi deploy

- [ ] Chạy migrations: `php artisan migrate --force`
- [ ] Chạy migration indexes: `php artisan migrate`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Cache events: `php artisan event:cache`
- [ ] Set permissions: `chmod -R 775 storage bootstrap/cache`
- [ ] Build assets: `npm run build`

### Kiểm tra Performance

- [ ] Test trang chủ - kiểm tra thời gian load
- [ ] Test trang phim - kiểm tra queries
- [ ] Test booking flow - kiểm tra performance
- [ ] Kiểm tra cache hoạt động đúng
- [ ] Kiểm tra database indexes đã được tạo
- [ ] Test với Google PageSpeed Insights

## 🚀 Lệnh tối ưu nhanh

```bash
# Tối ưu autoloader
composer install --optimize-autoloader --no-dev

# Cache tất cả
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear application cache
php artisan cache:clear

# Build assets
npm run build
```

## 📊 Monitoring

### Kiểm tra slow queries

```sql
-- MySQL
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 1;

-- Xem slow queries
SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 10;
```

### Kiểm tra cache hit rate

```bash
# Redis
redis-cli INFO stats | grep keyspace
```

## 🔧 Tối ưu thêm (tùy chọn)

### 1. CDN cho assets
- Sử dụng CloudFlare hoặc AWS CloudFront
- Upload images lên S3 hoặc Cloudinary

### 2. Database Read Replicas
- Tách read/write queries
- Sử dụng cho các trang public

### 3. Queue cho heavy tasks
- Email sending
- PDF generation
- Image processing

### 4. Full Page Caching
- Sử dụng Varnish hoặc Nginx cache
- Cache toàn bộ trang chủ

### 5. Image Optimization
- Sử dụng WebP format
- Lazy loading images
- Responsive images

## 📈 Expected Performance

Sau khi tối ưu, mong đợi:

- **Homepage**: < 500ms
- **Movie listing**: < 300ms
- **Movie detail**: < 400ms
- **Booking page**: < 600ms
- **Database queries**: < 50ms mỗi query

## 🐛 Troubleshooting

### Cache không hoạt động
```bash
# Kiểm tra cache driver
php artisan config:show cache

# Test cache
php artisan tinker
>>> Cache::put('test', 'value', 60);
>>> Cache::get('test');
```

### Queries chậm
```bash
# Enable query log
DB::enableQueryLog();
// ... your code ...
dd(DB::getQueryLog());
```

### Assets không load
```bash
# Rebuild assets
npm run build
# Hoặc
npm run production
```

