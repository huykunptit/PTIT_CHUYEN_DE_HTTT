#!/bin/sh
# Don't exit on error - we want to handle errors gracefully
set +e

echo "Starting application..."

# Create .env file from environment variables if it doesn't exist (do this first)
if [ ! -f .env ]; then
    echo "Creating .env file from environment variables..."
    touch .env
    
    # Write basic Laravel config
    echo "APP_NAME=${APP_NAME:-Laravel}" >> .env
    echo "APP_ENV=${APP_ENV:-production}" >> .env
    echo "APP_KEY=${APP_KEY:-}" >> .env
    echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env
    echo "APP_URL=${APP_URL:-http://localhost}" >> .env
    echo "" >> .env
    
    # Database config
    echo "DB_CONNECTION=${DB_CONNECTION:-mysql}" >> .env
    echo "DB_HOST=${DB_HOST:-127.0.0.1}" >> .env
    echo "DB_PORT=${DB_PORT:-3306}" >> .env
    echo "DB_DATABASE=${DB_DATABASE:-}" >> .env
    echo "DB_USERNAME=${DB_USERNAME:-}" >> .env
    echo "DB_PASSWORD=${DB_PASSWORD:-}" >> .env
    echo "" >> .env
    
    # Cache & Session
    echo "CACHE_DRIVER=${CACHE_DRIVER:-file}" >> .env
    echo "SESSION_DRIVER=${SESSION_DRIVER:-file}" >> .env
    echo "QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}" >> .env
    echo "" >> .env
    
    # Redis (if set)
    if [ -n "$REDIS_HOST" ]; then
        echo "REDIS_HOST=${REDIS_HOST}" >> .env
        echo "REDIS_PASSWORD=${REDIS_PASSWORD:-null}" >> .env
        echo "REDIS_PORT=${REDIS_PORT:-6379}" >> .env
        echo "" >> .env
    fi
    
    # Logging
    echo "LOG_CHANNEL=${LOG_CHANNEL:-stack}" >> .env
    echo "LOG_LEVEL=${LOG_LEVEL:-error}" >> .env
    echo "" >> .env
    
    # Mail
    if [ -n "$MAIL_MAILER" ]; then
        echo "MAIL_MAILER=${MAIL_MAILER}" >> .env
        echo "MAIL_HOST=${MAIL_HOST:-}" >> .env
        echo "MAIL_PORT=${MAIL_PORT:-587}" >> .env
        echo "MAIL_USERNAME=${MAIL_USERNAME:-}" >> .env
        echo "MAIL_PASSWORD=${MAIL_PASSWORD:-}" >> .env
        echo "MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}" >> .env
        echo "MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-}" >> .env
        echo "MAIL_FROM_NAME=${MAIL_FROM_NAME:-}" >> .env
        echo "" >> .env
    fi
    
    # Add other environment variables that start with specific prefixes
    env | grep -E '^(VNPAY_|GOOGLE_|PUSHER_|SPEEDSMS_|TMDB_|OTP_|SEPAY_)' >> .env || true
fi

# Generate application key if not set
if [ -z "$APP_KEY" ] || ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating application key..."
    php artisan key:generate --force 2>&1 || echo "Warning: Could not generate APP_KEY, please set it manually"
fi

# Wait for database to be ready (if using external database)
if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ]; then
    echo "Waiting for database connection..."
    max_attempts=60
    attempt=0
    db_ready=0
    
    # Test database connection using PHP PDO
    until php -r "
        try {
            \$pdo = new PDO(
                'mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT') ?: '3306'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );
            exit(0);
        } catch(Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        attempt=$((attempt + 1))
        if [ $attempt -ge $max_attempts ]; then
            echo "⚠️  Database connection failed after $max_attempts attempts"
            echo "⚠️  Migrations will be skipped. Please check your database configuration."
            db_ready=0
            break
        fi
        if [ $((attempt % 5)) -eq 0 ]; then
            echo "Database is unavailable - sleeping (attempt $attempt/$max_attempts)..."
        fi
        sleep 2
    done
    
    if [ $attempt -lt $max_attempts ]; then
        echo "✅ Database is ready!"
        db_ready=1
    fi
else
    echo "⚠️  Database configuration not found. Skipping database checks."
    db_ready=0
fi

# Run migrations only if database is ready
if [ "$db_ready" -eq 1 ]; then
    echo "Running migrations..."
    if php artisan migrate --force 2>&1; then
        echo "✅ Migrations completed successfully"
        if [ "${RUN_DB_SEED:-false}" = "true" ]; then
            echo "Running database seeders..."
            if php artisan db:seed --force 2>&1; then
                echo "✅ Seeders executed successfully"
            else
                echo "⚠️  Seeders failed. Continuing startup..."
            fi
        fi
    else
        echo "⚠️  Migrations failed, but continuing..."
    fi
else
    echo "⚠️  Skipping migrations - database not ready"
fi

# Clear and cache config (only if database is ready or not needed)
echo "Optimizing application..."
php artisan config:clear 2>/dev/null || true
if [ "$db_ready" -eq 1 ]; then
    php artisan config:cache 2>&1 || echo "⚠️  Config cache failed"
    php artisan route:cache 2>&1 || echo "⚠️  Route cache failed"
    php artisan view:cache 2>&1 || echo "⚠️  View cache failed"
else
    echo "⚠️  Skipping cache - database not ready"
fi

# Create storage link (skip if already exists)
php artisan storage:link 2>/dev/null || rm -f public/storage && php artisan storage:link || true

# Start supervisor
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

