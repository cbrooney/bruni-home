#docker build . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="arm32v7/nginx:stable-alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:test
docker build --platform linux/arm64/v8 . -f docker/php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-bullseye" --tag ghcr.io/cbrooney/bruni-home-php-fpm-rasp:test
