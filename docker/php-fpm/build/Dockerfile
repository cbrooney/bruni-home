FROM php:7.4.30-fpm-bullseye as common

LABEL org.opencontainers.image.source="https://github.com/cbrooney/bruni-home"

COPY --chown=www-data:www-data . /var/www/html

RUN export XDEBUG_MODE=off && \
    docker-php-ext-install pdo pdo_mysql && \
    apt-get update && \
    apt-get install -y librabbitmq-dev libssh-dev unzip libzip-dev libicu-dev libfcgi-bin && \
    echo "\n" | pecl install amqp && \
    echo "\n" | pecl install apcu && \
    docker-php-ext-enable amqp && \
    docker-php-ext-enable apcu && \
    docker-php-ext-install zip && \
    docker-php-ext-install intl && \
    docker-php-ext-enable intl && \
    docker-php-ext-install opcache && \
    addgroup --system runner && \
    adduser \
        --disabled-password \
        --gecos "" \
        --home /home/runner \
        --ingroup runner \
        --uid 10000 \
        runner

COPY docker/config/php-local.ini /usr/local/etc/php/conf.d/php-symfony.ini

RUN export XDEBUG_MODE=off && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer && \
    rm -f composer-setup.php && \
    echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' > /etc/apt/sources.list.d/symfony-cli.list && \
    apt-get update && \
    apt-get install -y symfony-cli nano vim less htop git autoconf build-essential wget openssh-client && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apt-get -y autoremove && \
    chown www-data:www-data -R /var/www && \
    rm -r /var/lib/apt/lists /var/cache/apt/archives

USER root

USER www-data
