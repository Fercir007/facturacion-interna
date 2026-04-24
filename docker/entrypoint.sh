#!/bin/sh
set -e

echo "==> Optimizando configuración Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Corriendo migraciones..."
php artisan migrate --force

echo "==> Iniciando PHP-FPM en background..."
php-fpm -D

echo "==> Iniciando Nginx..."
exec nginx -g "daemon off;"