############################
# 1. Composer dependencies
############################
FROM composer:2 AS composer

RUN docker-php-ext-install exif

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts

############################
# 2. Node build (Vite)
############################
FROM node:20 AS node

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .
COPY --from=composer /app/vendor ./vendor

RUN npm run build

############################
# 3. PHP runtime + nginx (un solo contenedor, sirve HTTP en :80)
############################
FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    git \
    nano \
    unzip \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd

WORKDIR /var/www/html

COPY --from=composer /app/vendor ./vendor
COPY . .
COPY --from=node /app/public/build ./public/build

RUN mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        bootstrap/cache \
        /var/log/nginx \
        /run/nginx \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && (useradd --system --no-create-home --user-group nginx || true) \
    && chown -R nginx:nginx /var/log/nginx /run/nginx

RUN { \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'memory_limit = 256M'; \
} > /usr/local/etc/php/conf.d/uploads.ini

RUN rm -f /etc/nginx/sites-enabled/default

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/run.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

CMD ["/usr/local/bin/start-container"]
