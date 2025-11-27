# 🚀 QUICK START - WINDOWS

## ⚡ CÁC LỆNH CẦN CHẠY (THEO THỨ TỰ)

### Bước 1: Khởi động Docker Desktop
Đảm bảo Docker Desktop đang chạy.

### Bước 2: Build và khởi động containers

```powershell
docker-compose build
docker-compose up -d
```

### Bước 3: Cài đặt dependencies

```powershell
docker exec cinema_app composer install
docker exec cinema_app npm install
docker exec cinema_app npm run build
```

### Bước 4: Cấu hình Laravel

```powershell
docker exec cinema_app php artisan key:generate
docker exec cinema_app php artisan storage:link
docker exec cinema_app php artisan optimize:clear
```

### Bước 5: Database setup

```powershell
docker exec cinema_app php artisan migrate --seed
```

### Bước 6: Kiểm tra services

```powershell
docker-compose ps
```

---

## 📋 HOẶC CHẠY FILE TỰ ĐỘNG

### PowerShell:
```powershell
.\WINDOWS_COMMANDS.ps1
```

### Command Prompt:
```cmd
WINDOWS_COMMANDS.bat
```

---

## ✅ KIỂM TRA SERVICES ĐANG CHẠY

```powershell
# Xem status
docker-compose ps

# Xem logs
docker-compose logs -f

# Kiểm tra từng service
docker-compose logs -f app
docker-compose logs -f queue
docker-compose logs -f scheduler
docker-compose logs -f redis
```

---

## 🔗 TRUY CẬP

- **Web App:** http://localhost:8089
- **phpMyAdmin:** http://localhost:8081
- **MySQL:** localhost:3308
- **Redis:** localhost:6379

---

## 🛠️ LỆNH THƯỜNG DÙNG

```powershell
# Restart services
docker-compose restart

# Xem logs
docker-compose logs -f [service_name]

# Vào shell container
docker exec -it cinema_app sh

# Chạy artisan commands
docker exec cinema_app php artisan [command]

# Clear cache
docker exec cinema_app php artisan optimize:clear
```

---

## 📚 XEM CHI TIẾT

Xem file `docs/WINDOWS_SETUP.md` để biết hướng dẫn đầy đủ.

