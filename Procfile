web: php artisan storage:link 2>/dev/null; heroku-php-apache2 public/
release: mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions storage/logs && chmod -R 775 storage bootstrap/cache && php artisan view:clear && php artisan config:clear && php artisan cache:clear && php artisan config:cache && php artisan route:cache
worker: php artisan queue:work --sleep=3 --tries=3 --timeout=90
# release: php artisan migrate --force && php artisan db:seed --force