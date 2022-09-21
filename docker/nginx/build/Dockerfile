ARG FPM_IMAGE
FROM ${FPM_IMAGE} as app

FROM nginx:alpine

USER root

COPY --chown=nginx:nginx . /var/www/html

#COPY --chown=nginx:nginx ../../../public /var/www/html/public
#COPY --chown=nginx:nginx ../bruni-home.conf /etc/nginx/conf.d/site.conf

USER 10000
