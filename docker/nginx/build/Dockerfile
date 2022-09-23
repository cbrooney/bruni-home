#ARG FPM_IMAGE
#FROM ${FPM_IMAGE} as php-fpm

ARG BASE_IMAGE
FROM ${BASE_IMAGE} as nginx-base

USER root

COPY --chown=nginx:nginx ./public /var/www/html/public
COPY --chown=nginx:nginx ./docker/nginx/bruni-home.conf /etc/nginx/conf.d/site.conf
#COPY --chown=nginx:nginx --from=php-fpm /var/www/html/public /var/www/public
#COPY --chown=nginx:nginx --from=php-fpm /var/www/html/docker/nginx/bruni-home.conf /etc/nginx/conf.d/site.conf

#COPY --chown=nginx:nginx ../../../public /var/www/html/public
#COPY --chown=nginx:nginx ../bruni-home.conf /etc/nginx/conf.d/site.conf

EXPOSE 8000

USER root
