# build dev env
# docker build . -f docker/php-fpm/build/Dockerfile-dev --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --target dev-env

# run dev env, and build
docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d --build

docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d --remove-orphans

docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down --remove-orphans