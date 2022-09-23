docker pull ghcr.io/cbrooney/bruni-home-php-fpm:latest

docker pull ghcr.io/cbrooney/bruni-home-nginx:latest

docker-compose --env-file .env.local.docker-compose -f docker-compose-prod.yml up --build -d

