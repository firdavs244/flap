FROM php:8.1-fpm

# PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libonig-dev libxml2-dev libzip-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip curl

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
