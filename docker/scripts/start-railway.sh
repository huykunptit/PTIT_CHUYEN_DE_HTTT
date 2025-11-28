#!/bin/sh
set -e

echo "Starting Railway deployment..."

# Wait for database to be ready
if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ]; then
    echo "Waiting for database connection..."
    max_attempts=30
    attempt=0
    
    until php -r "
        try {
            \$pdo = new PDO(
                'mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT') ?: '3306'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );
            echo 'DB ready';
            exit(0);
        } catch(Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        attempt=$((attempt + 1))
        if [ $attempt -ge $max_attempts ]; then
            echo "⚠️  Database connection timeout, continuing anyway..."
            break
        fi
        sleep 2
    done
    
    if [ $attempt -lt $max_attempts ]; then
        echo "✅ Database is ready!"
    fi
fi

# Run migrations if enabled
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force || echo "⚠️  Migrations failed or already run"
fi

# Optimize Laravel
echo "Optimizing application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Create storage link
php artisan storage:link || true

# Start supervisor (manages nginx and php-fpm)
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

