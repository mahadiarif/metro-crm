#!/bin/bash
set -e

echo "🚀 Starting Deployment..."

# Enter maintenance mode
php artisan down || true

# Pull latest changes
git pull origin main

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Run database migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Install NPM dependencies and build assets
if [ -f "package.json" ]; then
    npm install
    npm run build
fi

# Exit maintenance mode
php artisan up

echo "✅ Deployment Successful!"
