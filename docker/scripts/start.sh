#!/bin/sh
set -e

echo "Starting application..."

# Wait for database to be ready (if using external database)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database connection..."
    max_attempts=30
    attempt=0
    # Simple connection test using PHP
    until php -r "
        try {
            \$host = getenv('DB_HOST');
            \$port = getenv('DB_PORT') ?: 3306;
            \$connection = @fsockopen(\$host, \$port, \$errno, \$errstr, 2);
            if (\$connection) {
                fclose(\$connection);
                exit(0);
            }
            exit(1);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        attempt=$((attempt + 1))
        if [ $attempt -ge $max_attempts ]; then
            echo "Database connection failed after $max_attempts attempts"
            echo "Continuing anyway - migrations will retry..."
            break
        fi
        echo "Database is unavailable - sleeping (attempt $attempt/$max_attempts)"
        sleep 2
    done
    if [ $attempt -lt $max_attempts ]; then
        echo "Database is up!"
    fi
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force || true

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Create storage link
php artisan storage:link || true

# Start supervisor
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

