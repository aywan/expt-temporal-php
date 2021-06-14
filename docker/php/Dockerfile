#
# base image
#
FROM php:8.0-cli-alpine AS base

RUN apk add --no-cache \
        bash \
        sudo \
        coreutils \
        unzip \
        libpq \
    && docker-php-source extract \
    && apk add --no-cache --update --virtual .phpize-deps ${PHPIZE_DEPS} \
    && apk add --no-cache --update --virtual .dev-deps \
        postgresql-dev \
    \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install \
        pdo_pgsql \
        pcntl \
    \
    && apk del .dev-deps \
    && apk del .phpize-deps \
    && docker-php-source delete

WORKDIR /app

#
# prod image
#
FROM base as prod

COPY prod /usr/local/etc/php

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

#
# dev image
#
FROM base as dev

# https://pecl.php.net/package/xdebug
ARG XDEBUG_VER=3.0.3

RUN apk add --no-cache git \
    \
    && docker-php-source extract \
    && apk add --no-cache --update --virtual .phpize-deps ${PHPIZE_DEPS} \
    && pecl install \
        xdebug-${XDEBUG_VER} \
    \
    && docker-php-ext-enable xdebug \
    \
    && apk del .phpize-deps \
    && docker-php-source delete \
    \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer

COPY dev /usr/local/etc/php

COPY entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
