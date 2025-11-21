#!/bin/bash

# Script backup tự động cho Cinema project
# Sử dụng: ./backup.sh

set -e

# Cấu hình
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/cinemat"
PROJECT_DIR="/var/www/cinemat"
DB_NAME="cinemat_db"
DB_USER="cinemat_user"
DB_PASS="your_password_here"  # Thay bằng password thực tế

# Tạo thư mục backup nếu chưa có
mkdir -p $BACKUP_DIR

echo "🔄 Bắt đầu backup..."

# Backup database
echo "📦 Đang backup database..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql
gzip $BACKUP_DIR/db_$DATE.sql

# Backup files (chỉ backup storage và .env)
echo "📦 Đang backup files..."
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    $PROJECT_DIR/.env \
    $PROJECT_DIR/storage \
    $PROJECT_DIR/public/uploads 2>/dev/null || true

# Xóa backup cũ hơn 7 ngày
echo "🧹 Xóa backup cũ..."
find $BACKUP_DIR -type f -mtime +7 -delete

echo "✅ Backup hoàn tất!"
echo "📁 Vị trí: $BACKUP_DIR"
ls -lh $BACKUP_DIR | tail -5

