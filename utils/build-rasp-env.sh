# update branch
git pull

# only created locally on rasp, not pushed to github
docker build --platform linux/arm/v7 . -f docker/php-fpm/build/Dockerfile-prod --build-arg PHP_BASE_IMAGE="base-php-fpm-rasp" --build-arg PHP_BASE_IMAGE_TAG="php8.2" --build-arg APP_ENV="prod" --build-arg CONTAINER_USER_UID="1000" --build-arg CONTAINER_USER_GID="1000" --tag ghcr.io/cbrooney/bruni-home-app-rasp:php8.2
docker build --platform linux/arm/v7 . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="nginx:alpine" --tag ghcr.io/cbrooney/bruni-home-nginx-rasp:latest
