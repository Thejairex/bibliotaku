#!/bin/sh

set -e

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm &

nginx -g 'daemon off;'
