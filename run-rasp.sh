# run rasp in prod mode
docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose up -d --remove-orphans

# down only app => causes network error
#docker compose -f docker-compose-rasp.yml --env-file .env.local.docker-compose down

# down complete
#docker compose -f docker-compose-rasp.yml -f docker-compose-db.yml --env-file .env.local.docker-compose down --remove-orphans
#docker compose -f docker-compose-rasp.yml --env-file .env.local.docker-compose down --remove-orphans
