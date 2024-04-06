FROM php:8.3.4-fpm-bullseye

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    TZ="Asia/Yekaterinburg" \
    LANG="ru_RU.UTF-8"

RUN apt-get update && apt-get install -y locales git wget mc nano libffi-dev libfreetype6-dev libjpeg-dev libonig-dev libpng-dev libzip-dev zlib1g-dev libsodium-dev libicu-dev libmemcached-dev libmagickwand-dev build-essential libtool uuid-dev automake autotools-dev libltdl-dev libltdl7 libkrb5-dev libc-client-dev

RUN docker-php-ext-install pdo_mysql mysqli pcntl sockets bcmath iconv ffi mbstring opcache sodium intl zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd

RUN apt-get update \
    && apt-get install -y libc-client-dev libkrb5-dev \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

# RUN pecl install apcu memcached redis imagick \
#     && docker-php-ext-enable apcu memcached redis imagick

RUN apt-get clean && apt-get autoclean && apt-get autoremove && rm -rf /var/lib/apt/lists/*

# Ставим Composer'а.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER www-data:www-data

WORKDIR /var/www/

# Запускаем контейнер
CMD ["php-fpm", "-R"]
