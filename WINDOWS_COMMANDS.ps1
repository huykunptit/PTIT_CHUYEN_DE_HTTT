# Cinemat - Windows PowerShell Commands
# Chạy file này trong PowerShell: .\WINDOWS_COMMANDS.ps1

Write-Host "=== CINEMAT - WINDOWS SETUP ===" -ForegroundColor Cyan

# Function để chạy lệnh trong container
function Run-InContainer {
    param($container, $command)
    docker exec $container $command
}

# 1. Build và khởi động
Write-Host "`n1. Building and starting containers..." -ForegroundColor Yellow
docker-compose build
docker-compose up -d

# Đợi containers khởi động
Start-Sleep -Seconds 10

# 2. Install dependencies
Write-Host "`n2. Installing dependencies..." -ForegroundColor Yellow
Run-InContainer "cinema_app" "composer install"
Run-InContainer "cinema_app" "npm install"
Run-InContainer "cinema_app" "npm run build"

# 3. Laravel setup
Write-Host "`n3. Setting up Laravel..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Run-InContainer "cinema_app" "cp .env.example .env"
}
Run-InContainer "cinema_app" "php artisan key:generate"
Run-InContainer "cinema_app" "php artisan storage:link"
Run-InContainer "cinema_app" "php artisan optimize:clear"

# 4. Database setup
Write-Host "`n4. Setting up database..." -ForegroundColor Yellow
Write-Host "Running migrations..." -ForegroundColor Gray
Run-InContainer "cinema_app" "php artisan migrate --force"

# 5. Check services
Write-Host "`n5. Checking services status..." -ForegroundColor Yellow
docker-compose ps

Write-Host "`n=== SETUP COMPLETE ===" -ForegroundColor Green
Write-Host "Access the application at: http://localhost:8089" -ForegroundColor Cyan
Write-Host "phpMyAdmin at: http://localhost:8081" -ForegroundColor Cyan

Write-Host "`nUseful commands:" -ForegroundColor Yellow
Write-Host "  View logs: docker-compose logs -f" -ForegroundColor Gray
Write-Host "  Stop: docker-compose down" -ForegroundColor Gray
Write-Host "  Restart: docker-compose restart" -ForegroundColor Gray
Write-Host "  Shell: docker exec -it cinema_app sh" -ForegroundColor Gray

