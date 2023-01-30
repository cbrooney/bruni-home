#docker-compose --env-file .env.local.docker-compose -f docker-compose-prod.yml pull

docker pull ghcr.io/cbrooney/bruni-home-php-fpm:latest

docker pull ghcr.io/cbrooney/bruni-home-nginx:latest

docker-compose --env-file .env.local.docker-compose -f docker-compose-prod.yml up -d

# run app with separate container for db
# docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d
# docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml --env-file .env.test up -d

# run container for tests and wait for them being up
#docker-compose -f docker-compose.yaml -f docker-compose-test.yaml run php-fpm utils/wait-for-it.sh database:3306 -t 60
#docker-compose -f docker-compose-dev.yml --env-file .env.local.docker-compose run php-fpm utils/wait-for-it.sh mysql:3306 -t 60

# run tests
#docker-compose -f docker-compose.yaml -f docker-compose-test.yaml run php-fpm composer tests
docker-compose -f docker-compose-dev.yml --env-file .env.test run php-fpm composer tests

# down
docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml down -v
docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down
docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml --env-file .env.test down

#docker-compose --env-file .env.local.docker-compose -f docker-compose-prod.yml up --build -d
#docker-compose --env-file .env.local.docker-compose -f docker-compose-prod.yml up --pull always -d
