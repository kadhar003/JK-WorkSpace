# Stage 1: Build Frontend Assets (Node 18)
FROM node:18-alpine AS frontend

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci --prefer-offline --no-audit

# Copy entire application
COPY . .

# Build frontend assets with Vite
RUN npm run build

# Stage 2: Backend Build (PHP 8.2 with necessary extensions)
FROM php:8.2-fpm-alpine AS backend

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    postgresql-dev \
    icu-dev \
    libmagic-dev \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    pdo \
    pdo_sqlite \
    pdo_mysql \
    pdo_pgsql \
    opcache \
    zip \
    intl \
    && docker-php-ext-configure opcache --enable-opcache \
    && apk del --no-cache $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/* /tmp/*

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application files
COPY . .

# Copy built frontend assets from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Set proper permissions for app files
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Install PHP dependencies (production only, no dev dependencies)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist

# Run post-install scripts
RUN composer run-script post-install-cmd

# Create necessary directories
RUN mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/app \
    && mkdir -p /var/www/bootstrap/cache \
    && touch /var/www/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/storage

# Set Laravel environment
ENV APP_ENV=production
ENV APP_DEBUG=false

# Clear caches
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

USER www-data

# Stage 3: Nginx Web Server (Production)
FROM nginx:alpine AS webserver

WORKDIR /var/www

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Copy application code and built assets from backend stage
COPY --from=backend /var/www /var/www

# Set proper permissions
RUN chown -R nginx:nginx /var/www

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]

# Stage 4: PHP-FPM Application Server
FROM backend AS app

EXPOSE 9000

USER www-data

CMD ["php-fpm"]