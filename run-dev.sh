# build dev env
docker build . -f docker/php-fpm/build/Dockerfile-dev --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --target dev-env

# run dev env
docker-compose -f docker-compose.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d --build

#