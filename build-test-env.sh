docker build . -f docker/php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --tag ghcr.io/cbrooney/bruni-home-php-fpm:test-env
docker push ghcr.io/cbrooney/bruni-home-php-fpm:test-env