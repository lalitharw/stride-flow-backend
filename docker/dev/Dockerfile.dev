FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache bash curl libpng-dev libzip-dev zip unzip oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd opcache


EXPOSE 9000

CMD ["php-fpm","-F"]