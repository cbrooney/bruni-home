# update branch
git pull

# only created locally on rasp, not pushed to github
docker build . -f docker/php-fpm/build/Dockerfile-prod --build-arg APP_ENV="prod" --build-arg CONTAINER_USER_UID="1000" --build-arg BASE_IMAGE_PHP="base-php-fpm-rasp:8.0.27" --tag ghcr.io/cbrooney/bruni-home-app-rasp:latest
docker build . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="nginx:alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:latest
