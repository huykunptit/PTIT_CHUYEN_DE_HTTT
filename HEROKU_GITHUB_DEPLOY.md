# Deploy Laravel lên Heroku qua GitHub

## Yêu cầu
- Tài khoản Heroku
- Repository trên GitHub
- Heroku CLI (tùy chọn, để cấu hình config vars)

## Bước 1: Push code lên GitHub

```bash
git add .
git commit -m "Prepare for Heroku deployment"
git push origin main
```

## Bước 2: Tạo App trên Heroku

1. Đăng nhập Heroku Dashboard: https://dashboard.heroku.com
2. Click **"New"** → **"Create new app"**
3. Đặt tên app (VD: `cinemat-booking`)
4. Chọn region (United States hoặc Europe)
5. Click **"Create app"**

## Bước 3: Kết nối GitHub

1. Trong app dashboard, chọn tab **"Deploy"**
2. Ở mục **"Deployment method"**, chọn **"GitHub"**
3. Click **"Connect to GitHub"** và authorize
4. Tìm repository của bạn và click **"Connect"**

## Bước 4: Thêm Database Add-on

1. Chọn tab **"Resources"**
2. Trong **"Add-ons"**, tìm **"JawsDB MySQL"**
3. Chọn plan **"Kitefin - Free"** (hoặc plan phù hợp)
4. Click **"Submit Order Form"**

## Bước 5: Cấu hình Environment Variables

Chọn tab **"Settings"** → **"Reveal Config Vars"** và thêm:

| Key | Value |
|-----|-------|
| `APP_NAME` | Cinemat |
| `APP_ENV` | production |
| `APP_DEBUG` | false |
| `APP_KEY` | (chạy `php artisan key:generate --show` ở local) |
| `APP_URL` | https://your-app-name.herokuapp.com |
| `LOG_CHANNEL` | errorlog |
| `DB_CONNECTION` | mysql |
| `CACHE_DRIVER` | file |
| `SESSION_DRIVER` | cookie |
| `QUEUE_CONNECTION` | sync |

**Lưu ý:** JawsDB sẽ tự động thêm `JAWSDB_URL`. Laravel đã được cấu hình để parse URL này.

### Cấu hình Database từ JAWSDB_URL

Nếu muốn set manual, parse `JAWSDB_URL`:
```
mysql://username:password@host:3306/database_name
```

Thêm các biến:
| Key | Value |
|-----|-------|
| `DB_HOST` | (host từ URL) |
| `DB_PORT` | 3306 |
| `DB_DATABASE` | (database name từ URL) |
| `DB_USERNAME` | (username từ URL) |
| `DB_PASSWORD` | (password từ URL) |

## Bước 6: Deploy

### Option A: Manual Deploy
1. Tab **"Deploy"** → scroll xuống **"Manual deploy"**
2. Chọn branch (VD: `main`)
3. Click **"Deploy Branch"**

### Option B: Automatic Deploys
1. Tab **"Deploy"** → **"Automatic deploys"**
2. Chọn branch muốn auto-deploy
3. Click **"Enable Automatic Deploys"**
4. Mỗi khi push lên branch đó, Heroku sẽ tự động deploy

## Bước 7: Chạy Migration (Lần đầu)

### Qua Heroku CLI:
```bash
heroku run php artisan migrate --force -a your-app-name
heroku run php artisan db:seed --force -a your-app-name
```

### Qua Heroku Console (Dashboard):
1. Tab **"More"** → **"Run console"**
2. Nhập: `php artisan migrate --force`
3. Enter để chạy

## Bước 8: Kiểm tra

Truy cập: `https://your-app-name.herokuapp.com`

## Troubleshooting

### Xem logs:
```bash
heroku logs --tail -a your-app-name
```

Hoặc trong Dashboard: Tab **"More"** → **"View logs"**

### Lỗi 500:
- Kiểm tra `APP_KEY` đã được set chưa
- Kiểm tra database connection
- Xem logs để debug

### Lỗi database:
- Kiểm tra `JAWSDB_URL` có được add chưa
- Đảm bảo `DB_CONNECTION=mysql`

## Cấu trúc file quan trọng

```
Procfile                 # Cấu hình web server
composer.json           # Dependencies + scripts
app.json                # Heroku app manifest
config/database.php     # Database config (đã hỗ trợ JAWSDB_URL)
```

## Tips

1. **Đừng commit .env** - sử dụng Config Vars trên Heroku
2. **APP_KEY** - generate ở local rồi paste vào Config Vars
3. **Storage** - Heroku filesystem là ephemeral, dùng S3 cho file uploads
4. **SSL** - Heroku cung cấp SSL miễn phí, đảm bảo `APP_URL` dùng `https://`
