FROM php:7.4-cli-alpine

RUN addgroup -g 1000 www \
    && adduser -D -u 1000 -G www www

RUN apk add --no-cache zlib-dev libzip-dev

RUN docker-php-ext-install opcache zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN set -eux; \
    apk add --no-cache --virtual .build-deps ${PHPIZE_DEPS}; \
    pecl install xdebug ; \
    pecl clear-cache; \
    docker-php-ext-enable xdebug ;\
    apk del .build-deps;

WORKDIR /var/www/html

USER www
