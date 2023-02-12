# update branch
git pull

# create images from prod
docker build . -f docker/nginx/build/Dockerfile --build-arg BASE_IMAGE_NGINX="nginx:alpine" --tag ghcr.io/cbrooney/bruni-home-nginx:latest
docker build . -f docker/php-fpm/build/Dockerfile-prod --build-arg PHP_BASE_IMAGE="base-php-fpm-prod" --build-arg PHP_BASE_IMAGE_TAG="8.0.27" --build-arg APP_ENV="prod" --build-arg CONTAINER_USER_UID="1000" --build-arg CONTAINER_USER_GID="1000" --tag ghcr.io/cbrooney/bruni-home-app-prod:latest
