# create image for tests for this branch
docker build . -f docker/php-fpm/build/Dockerfile-dev --build-arg PHP_BASE_IMAGE_TAG="latest" --tag ghcr.io/cbrooney/bruni-home-app-test:${GITHUB_HEAD_REF}
# docker push ghcr.io/cbrooney/bruni-home-app-test:${GITHUB_HEAD_REF}
