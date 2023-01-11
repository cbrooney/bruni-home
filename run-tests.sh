docker pull ghcr.io/cbrooney/bruni-home-php-fpm:test-env
docker-compose -f docker-compose-db.yml -f docker-compose-php-test.yml --env-file .env.test up -d
docker-compose -f docker-compose-php-test.yml --env-file .env.test run php-fpm utils/wait-for-it.sh mysql:3306 -t 60
docker-compose -f docker-compose-php-test.yml --env-file .env.test run php-fpm composer tests
docker-compose -f docker-compose-db.yml -f docker-compose-php-test.yml --env-file .env.test down
