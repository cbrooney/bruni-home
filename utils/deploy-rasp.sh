docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down --remove-orphans

bash build-rasp-env.sh
bash run-rasp.sh

docker run \
        --name php-fpm \
        --workdir /var/www/html \
        bin/console d:m:m

docker exec -it php-fpm bash
