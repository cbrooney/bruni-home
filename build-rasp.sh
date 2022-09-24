#docker build . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="arm32v7/nginx:stable-alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:test
docker build . -f docker/php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-bullseye:6b9ed33b1b393e6c3ebe8c0e7a8a9fe34264ed16b6dc31518248805f8abedbb8" --tag ghcr.io/cbrooney/bruni-home-php-fpm-rasp:test
