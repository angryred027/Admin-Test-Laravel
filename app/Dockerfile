# FROM php:8.2-fpm-alpine3.17
FROM php:8.3-fpm-alpine3.19

# tinker(psysh)
ARG PSYSH_DIR=/usr/local/share/psysh
ARG PSYSH_PHP_MANUAL=$PSYSH_DIR/php_manual.sqlite
ARG PHP_MANUAL_URL=http://psysh.org/manual/ja/php_manual.sqlite

# composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer

# phpcs(for php8)
ENV PHP_CS_FIXER_IGNORE_ENV 1

RUN apk update && \
  apk add --update --no-cache linux-headers --virtual=.build-dependencies \
  #   autoconf=~2.69 \
  #   gcc=~9.2 \
  #   g++=~9.2 \
  #   make=~4.2 \
  #   tzdata=2019c-r0 \
  #   git=~2.24 && \
  # apk add --update --no-cache \
  # icu-dev=~64.2 \
  # libzip-dev=~1.5 \
  # oniguruma-dev=~6.9 && \
    autoconf \
    gcc \
    g++ \
    make \
    tzdata \
    git && \
  apk add --update --no-cache \
  icu-dev \
  libzip-dev \
  oniguruma-dev && \
  pecl install xdebug && \
  git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis && \
  pecl install xhprof && \
  apk del .build-dependencies && \
  docker-php-ext-install intl pdo_mysql mbstring zip bcmath redis && \
  docker-php-ext-enable xdebug && \
  docker-php-ext-enable xhprof && \
  mkdir $PSYSH_DIR && curl $PHP_MANUAL_URL -o $PSYSH_PHP_MANUAL && \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.jp
  # && \
  # composer global require hirak/prestissimo

RUN chown www-data:www-data -R /var/www
USER www-data

WORKDIR /var/www/html
