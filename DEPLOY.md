# Hướng dẫn Deploy lên Ubuntu Server

## Yêu cầu hệ thống

- Ubuntu 20.04 LTS hoặc cao hơn
- PHP 8.1+ với các extension: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- MySQL 8.0+ hoặc MariaDB 10.3+
- Composer
- Node.js 18+ và NPM
- Nginx hoặc Apache

## Bước 1: Cài đặt dependencies trên server

```bash
# Cập nhật hệ thống
sudo apt update && sudo apt upgrade -y

# Cài đặt PHP và extensions
sudo apt install -y php8.1-fpm php8.1-cli php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath

# Cài đặt MySQL
sudo apt install -y mysql-server

# Cài đặt Nginx
sudo apt install -y nginx

# Cài đặt Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Cài đặt Node.js và NPM
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

## Bước 2: Cấu hình MySQL

```bash
sudo mysql_secure_installation

# Tạo database và user
sudo mysql -u root -p
```

```sql
CREATE DATABASE cinemat_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cinemat_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON cinemat_db.* TO 'cinemat_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Bước 3: Upload code lên server

```bash
# Tạo thư mục project
sudo mkdir -p /var/www/cinemat
sudo chown -R $USER:$USER /var/www/cinemat

# Upload code (sử dụng git, scp, hoặc rsync)
cd /var/www/cinemat
git clone your-repository-url .
# hoặc
# scp -r ./your-project/* user@server:/var/www/cinemat/
```

## Bước 4: Cài đặt dependencies

```bash
cd /var/www/cinemat

# Cài đặt PHP dependencies
composer install --optimize-autoloader --no-dev

# Cài đặt NPM dependencies
npm install
npm run build

# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

## Bước 5: Cấu hình .env

Chỉnh sửa file `.env`:

```env
APP_NAME="Cinema"
APP_ENV=production
APP_KEY=base64:... (đã được generate)
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinemat_db
DB_USERNAME=cinemat_user
DB_PASSWORD=your_strong_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# VNPay Configuration
VNPAY_URL=https://www.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_RETURN_URL=https://your-domain.com/payment/vnpay/return
VNPAY_IPN_URL=https://your-domain.com/payment/vnpay/ipn

# SePay Configuration
SEPAY_WEBHOOK_TOKEN=your_webhook_token
SEPAY_MATCH_PATTERN=SE
```

## Bước 6: Chạy migrations và optimize

```bash
# Chạy migrations
php artisan migrate --force

# Tối ưu cho production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Bước 7: Cấu hình Nginx

Tạo file cấu hình Nginx:

```bash
sudo nano /etc/nginx/sites-available/cinemat
```

Nội dung:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/cinemat/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json;
}
```

Kích hoạt site:

```bash
sudo ln -s /etc/nginx/sites-available/cinemat /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Bước 8: Cấu hình SSL (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

## Bước 9: Cấu hình Redis (tùy chọn nhưng khuyến nghị)

```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

## Bước 10: Cấu hình Queue Worker (nếu sử dụng queue)

```bash
sudo nano /etc/supervisor/conf.d/cinemat-worker.conf
```

Nội dung:

```ini
[program:cinemat-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cinemat/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/cinemat/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cinemat-worker:*
```

## Bước 11: Cấu hình Cron Jobs

```bash
sudo crontab -e -u www-data
```

Thêm dòng:

```
* * * * * cd /var/www/cinemat && php artisan schedule:run >> /dev/null 2>&1
```

## Bước 12: Cấu hình Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## Bước 13: Set permissions

```bash
sudo chown -R www-data:www-data /var/www/cinemat
sudo chmod -R 755 /var/www/cinemat
sudo chmod -R 775 /var/www/cinemat/storage
sudo chmod -R 775 /var/www/cinemat/bootstrap/cache
```

## Bước 14: Tối ưu PHP-FPM

```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

Tối ưu các giá trị:

```ini
memory_limit = 256M
max_execution_time = 60
upload_max_filesize = 20M
post_max_size = 20M
```

```bash
sudo systemctl restart php8.1-fpm
```

## Bước 15: Monitoring và Logs

```bash
# Xem logs Nginx
sudo tail -f /var/log/nginx/error.log

# Xem logs Laravel
tail -f /var/www/cinemat/storage/logs/laravel.log

# Xem logs PHP-FPM
sudo tail -f /var/log/php8.1-fpm.log
```

## Script tự động deploy

Tạo file `deploy.sh`:

```bash
#!/bin/bash

cd /var/www/cinemat

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear application cache
php artisan cache:clear

# Restart services
sudo systemctl reload php8.1-fpm
sudo systemctl reload nginx

echo "Deployment completed!"
```

Cấp quyền thực thi:

```bash
chmod +x deploy.sh
```

## Kiểm tra performance

Sau khi deploy, kiểm tra:

1. **Page Speed**: Sử dụng Google PageSpeed Insights
2. **Database**: Kiểm tra slow queries
3. **Cache**: Đảm bảo cache hoạt động đúng
4. **SSL**: Kiểm tra SSL rating

## Troubleshooting

### Lỗi 500 Internal Server Error
- Kiểm tra logs: `tail -f storage/logs/laravel.log`
- Kiểm tra permissions: `sudo chmod -R 775 storage bootstrap/cache`
- Kiểm tra .env file

### Lỗi database connection
- Kiểm tra thông tin DB trong .env
- Kiểm tra MySQL service: `sudo systemctl status mysql`

### Assets không load
- Chạy lại: `npm run build`
- Kiểm tra permissions của thư mục public

## Backup

Tạo script backup tự động:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/cinemat"

mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u cinemat_user -p cinemat_db > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/cinemat

# Xóa backup cũ hơn 7 ngày
find $BACKUP_DIR -type f -mtime +7 -delete
```

