#!/bin/bash
# docker-entrypoint.sh

# Generate APP_KEY if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Start PHP-FPM
php-fpm
