FROM php:7.4.30-fpm-buster as common

# LABEL org.opencontainers.image.source="https://github.com/cbrooney/bruni-home"

WORKDIR /var/www/html

USER root

ARG UID
ARG GID

RUN usermod -u "$UID" www-data \
    && groupmod -g "$GID" www-data
#    && usermod -m -d /home/www-data www-data

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
    docker-php-ext-install opcache

COPY docker/config/php-local.ini /usr/local/etc/php/conf.d/php-symfony.ini
COPY docker/config/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

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

#ARG GITHUB_OAUTH_TOKEN
#ARG SSH_PRIV_KEY
#RUN mkdir -p /home/www-data/.ssh /home/www-data/.composer \
#    && printf '{"github-oauth":{"github.com":"%s"}}' "$GITHUB_OAUTH_TOKEN" > /home/www-data/.composer/auth.json \
#    && echo -n $SSH_PRIV_KEY | base64 -d > /home/www-data/.ssh/id_rsa \
#    && chmod -R 600 /home/www-data/.ssh/* \
#    && chown -R www-data:www-data /home/www-data

#USER $UID

USER www-data

RUN cd /var/www/html \
    && composer install
