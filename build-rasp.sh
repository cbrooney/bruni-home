docker build --platform linux/arm/v7 . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="nginx:alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:test
docker build --platform linux/arm/v7 . -f docker/php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --tag ghcr.io/cbrooney/bruni-home-php-fpm-rasp:test

docker build --platform linux/arm/v7 . -f docker/php-fpm/build/Dockerfile-prod --build-arg APP_ENV="prod" --build-arg CONTAINER_USER_UID="1000" --tag ghcr.io/cbrooney/bruni-home-app-rasp:latest
docker build --platform linux/arm/v7 . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="nginx:alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:test

docker push ghcr.io/cbrooney/bruni-home-app-rasp:latest


docker build --platform linux/arm/v7 . -f php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --target prod-base --tag ghcr.io/cbrooney/base-php-fpm-rasp:latest
docker push ghcr.io/cbrooney/base-php-fpm-rasp:latest
docker build . -f php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --target dev-base --tag ghcr.io/cbrooney/base-php-fpm-dev:latest
docker push ghcr.io/cbrooney/base-php-fpm-dev:latest