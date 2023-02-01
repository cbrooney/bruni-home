# run dev env, and build
docker-compose -f docker-compose-dev.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d --build

docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d --remove-orphans

docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down --remove-orphans

docker compose -f docker-compose-rasp.yml --env-file .env.local.docker-compose down --remove-orphans