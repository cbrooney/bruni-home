docker-compose -f docker-compose-db.yml -f docker-compose.yml --env-file .env.test up -d --build

docker-compose -f docker-compose.yml --env-file .env.local.docker-compose run php-fpm utils/wait-for-it.sh mysql:3306 -t 60

docker-compose -f docker-compose.yml --env-file .env.local.docker-compose run php-fpm composer tests

docker-compose -f docker-compose.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down