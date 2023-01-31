docker build . -f docker/php-fpm/build/Dockerfile-dev --tag ghcr.io/cbrooney/bruni-home-php-fpm:test

docker build . -f Dockerfile.nginx.build --build-arg FPM_IMAGE="ghcr.io/cbrooney/bruni-home-php-fpm:test" --tag ghcr.io/cbrooney/bruni-home-nginx:test

#docker run -p 8001:8001 ghcr.io/cbrooney/bruni-home-nginx:test
#
#docker run -p 8000:8000 -p 9000:9000 ghcr.io/cbrooney/bruni-home-php-fpm:test

#php bin/console server:start --port=8080
