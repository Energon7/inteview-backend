#!/bin/bash

FILE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
pushd "$FILE_DIR"

docker-compose build
docker-compose up -d

docker-compose exec -u "$(id -u)" api-server composer install
docker-compose exec -u "$(id -u)" api-server php artisan key:generate
docker-compose exec -u "$(id -u)" api-server php artisan optimize:clear
docker-compose exec -u "$(id -u)" api-server php artisan optimize
docker-compose exec -u "$(id -u)" api-server php artisan migrate
docker-compose exec -u "$(id -u)" api-server composer dump-autoload
docker-compose exec -u "$(id -u)" api-server php artisan db:seed



popd
