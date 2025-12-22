FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    autoconf \
    gcc \
    g++ \
    make \
    nodejs \
    npm \
    nginx \
    supervisor \
    mariadb-dev \
    mysql-client \
    imagemagick \
    imagemagick-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql bcmath intl gd zip opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies (without running scripts that need artisan)
RUN composer install --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-dev --no-scripts

# Copy the rest of the application
COPY . .

# Now run composer scripts after all files are copied
RUN composer run-script post-autoload-dump

# Install Node.js dependencies and build assets (if needed)
RUN npm install && npm run build || true

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage directories if they don't exist
RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs

# Set permissions
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

CMD sh -c "php artisan migrate --force && php artisan db:seed --force && php artisan queue:work"
