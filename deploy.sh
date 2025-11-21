#!/bin/bash

# Script tự động deploy cho Ubuntu Server
# Sử dụng: ./deploy.sh

set -e

echo "🚀 Bắt đầu deployment..."

# Màu sắc cho output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Di chuyển đến thư mục project
cd /var/www/cinemat || { echo "❌ Không tìm thấy thư mục project!"; exit 1; }

echo -e "${YELLOW}📦 Pulling latest code...${NC}"
git pull origin main || git pull origin master

echo -e "${YELLOW}📥 Installing PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev --no-interaction

echo -e "${YELLOW}📥 Installing NPM dependencies...${NC}"
npm install --production

echo -e "${YELLOW}🏗️  Building assets...${NC}"
npm run build

echo -e "${YELLOW}🗄️  Running migrations...${NC}"
php artisan migrate --force

echo -e "${YELLOW}⚡ Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo -e "${YELLOW}🧹 Clearing application cache...${NC}"
php artisan cache:clear

echo -e "${YELLOW}🔄 Reloading services...${NC}"
sudo systemctl reload php8.1-fpm 2>/dev/null || sudo systemctl reload php8.0-fpm 2>/dev/null || true
sudo systemctl reload nginx

echo -e "${GREEN}✅ Deployment completed successfully!${NC}"

