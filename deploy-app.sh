git pull

docker build --platform linux/arm/v7 . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="nginx:alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:test
docker build --platform linux/arm/v7 . -f docker/php-fpm/build/Dockerfile --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --tag ghcr.io/cbrooney/bruni-home-php-fpm-rasp:test

docker compose --env-file .env.local.docker-compose -f docker-compose-rasp.yml down

# bash require-once.sh


# run migrations in build

docker compose --env-file .env.local.docker-compose -f docker-compose-rasp.yml up -d

# --recreate option
