# build prod env
#docker build . -f docker/php-fpm/build/Dockerfile-prod --build-arg APP_ENV="prod" --build-arg CONTAINER_USER_UID="1000" --tag ghcr.io/cbrooney/bruni-home-app-prod:test-local
#docker push ghcr.io/cbrooney/bruni-home-app-prod:test-local

# run prod env
docker-compose -f docker-compose-prod.yml -f docker-compose-db.yml --env-file .env.local.docker-compose pull
docker-compose -f docker-compose-prod.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d

# down
# docker-compose -f docker-compose-prod.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down