FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    mysql-client \
    libzip-dev \
    libpng-dev \
    zlib-dev \
    postgresql-dev \
  && docker-php-ext-configure gd \
  && docker-php-ext-install gd zip pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY ./conf/php.ini /usr/local/etc/php/conf.d/custom.ini
