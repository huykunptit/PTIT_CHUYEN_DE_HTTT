web: heroku-php-apache2 public/
release: php artisan view:clear && php artisan config:clear && php artisan cache:clear && php artisan config:cache && php artisan route:cache
worker: php artisan queue:work --sleep=3 --tries=3 --timeout=90
# release: php artisan migrate --force && php artisan db:seed --force