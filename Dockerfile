
# ==========================================
# STAGE 1: BUILD FRONTEND ASSETS
# ==========================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

COPY . .

RUN npm run build


# ==========================================
# STAGE 2: LARAVEL 13 + PHP 8.4 + APACHE
# ==========================================
FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    ca-certificates \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install -j"$(nproc)" \
    bcmath \
    curl \
    dom \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo_mysql \
    zip

# Enable Apache modules
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel source code
COPY . .

# TiDB Cloud SSL Certificate
COPY docker/certs/ca.pem /etc/ssl/certs/tidb-ca.pem

# Install production dependencies
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader

# Copy compiled Vite assets
COPY --from=frontend /app/public/build ./public/build

# Apache configuration
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Start script
COPY docker/start-apache.sh /usr/local/bin/start-apache

RUN chmod +x /usr/local/bin/start-apache

# Create Laravel writable directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# Default production environment
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 10000

CMD ["/usr/local/bin/start-apache"]
