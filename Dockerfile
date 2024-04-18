# Gunakan PHP 8.1 FPM Alpine sebagai base image
FROM php:8.1-fpm-alpine

# Instal nginx dan wget
RUN apk add --no-cache nginx wget

# Buat direktori yang diperlukan
RUN mkdir -p /run/nginx

# Salin konfigurasi nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Buat direktori app dan salin kode aplikasi
RUN mkdir -p /app
COPY . /app

# Salin file kredensial Firebase ke dalam image Docker
COPY resources/credentials/firebase_credentials.json /app/resources/credentials/firebase_credentials.json

# Atur izin file kredensial
RUN chmod +r /app/resources/credentials/firebase_credentials.json

# Instal Composer
RUN wget http://getcomposer.org/composer.phar -O /usr/local/bin/composer && chmod a+x /usr/local/bin/composer

# Instal dependencies menggunakan Composer
RUN cd /app && /usr/local/bin/composer install --no-dev

# Ganti kepemilikan direktori app
RUN chown -R www-data:www-data /app

# Instal dependensi untuk ekstensi gRPC
RUN apk add --no-cache $PHPIZE_DEPS \
    linux-headers \
    zlib-dev \
    g++

# Pasang ekstensi gRPC
RUN pecl install grpc && docker-php-ext-enable grpc

# Script untuk startup
CMD sh /app/docker/startup.sh
