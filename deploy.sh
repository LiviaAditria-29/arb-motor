#!/usr/bin/env bash
set -e
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
php artisan migrate --force