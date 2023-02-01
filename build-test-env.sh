# create image for tests for this branch
docker build . -f docker/php-fpm/build/Dockerfile-dev --build-arg BASE_IMAGE_PHP="php:7.4.30-fpm-buster" --tag ghcr.io/cbrooney/bruni-home-app-test:${GITHUB_HEAD_REF}
# docker push ghcr.io/cbrooney/bruni-home-app-test:${GITHUB_HEAD_REF}
