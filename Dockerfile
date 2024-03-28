FROM php:8.2.10-alpine3.18 as os

RUN apk add \
    libzip-dev \
    unzip \
    bash \
    librdkafka-dev
RUN apk add --update linux-headers --update-cache --virtual .build-deps ${PHPIZE_DEPS}\
    && pecl install redis rdkafka zip \
    && docker-php-ext-install \
    opcache \
    zip \
    && apk del .build-deps ${PHPIZE_DEPS} \
    && docker-php-source delete

RUN docker-php-ext-enable redis rdkafka zip
RUN apk add git
ENV COMPOSER_HOME /.composer
COPY --from=composer/composer:2-bin /composer /usr/bin/composer
COPY .docker/php.ini $PHP_INI_DIR/php.ini
COPY .docker/php/*.ini $PHP_INI_DIR/conf.d/
COPY . /app

WORKDIR /app

RUN composer install

CMD ["sleep", "infinity"]