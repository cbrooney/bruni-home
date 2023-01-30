# build prod env
docker build . -f docker/php-fpm/build/Dockerfile-prod --build-arg APP_ENV="prod"  --tag ghcr.io/cbrooney/bruni-home-app-prod:test-local
docker build . -f docker/php-fpm/build/Dockerfile-prod --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --build-arg APP_ENV="prod"  --tag ghcr.io/cbrooney/bruni-home-app-prod:test-local
docker push ghcr.io/cbrooney/bruni-home-app-prod:test-local

# run prod env
docker-compose -f docker-compose.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d
