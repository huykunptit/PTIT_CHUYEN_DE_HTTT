web: heroku-php-apache2 public/
release: php artisan migrate --force && php artisan db:seed --force
worker: php artisan queue:work --sleep=3 --tries=3 --timeout=90
