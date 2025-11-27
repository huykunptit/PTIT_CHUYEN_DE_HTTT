@echo off
REM Cinemat - Windows Batch Commands
REM Chạy file này trong Command Prompt

echo === CINEMAT - WINDOWS SETUP ===

REM 1. Build và khởi động
echo.
echo 1. Building and starting containers...
docker-compose build
docker-compose up -d

REM Đợi containers khởi động
timeout /t 10 /nobreak >nul

REM 2. Install dependencies
echo.
echo 2. Installing dependencies...
docker exec cinema_app composer install
docker exec cinema_app npm install
docker exec cinema_app npm run build

REM 3. Laravel setup
echo.
echo 3. Setting up Laravel...
if not exist .env (
    docker exec cinema_app cp .env.example .env
)
docker exec cinema_app php artisan key:generate
docker exec cinema_app php artisan storage:link
docker exec cinema_app php artisan optimize:clear

REM 4. Database setup
echo.
echo 4. Setting up database...
echo Running migrations...
docker exec cinema_app php artisan migrate --force

REM 5. Check services
echo.
echo 5. Checking services status...
docker-compose ps

echo.
echo === SETUP COMPLETE ===
echo Access the application at: http://localhost:8089
echo phpMyAdmin at: http://localhost:8081
echo.
echo Useful commands:
echo   View logs: docker-compose logs -f
echo   Stop: docker-compose down
echo   Restart: docker-compose restart
echo   Shell: docker exec -it cinema_app sh
echo.

pause

