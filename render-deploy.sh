#!/bin/bash
# Render deployment initialization script
set -e

echo "Starting Laravel application setup..."

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Run seeders (optional - comment out if not needed)
# echo "Seeding database..."
# php artisan db:seed

# Cache configuration for better performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Setup complete!"
