FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && rm -rf /var/lib/apt/lists/*

RUN sed -i 's/^;*clear_env = .*/clear_env = no/' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/public/uploads

USER www-data
