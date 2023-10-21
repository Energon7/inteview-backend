migrate:
	./docker/exec.sh php artisan migrate

migrate-fresh:
	./docker/exec.sh php artisan migrate:fresh

init:
	./docker/init.sh

start:
	./docker/start.sh

ssh:
	./docker/exec.sh bash

test:
	./docker/exec.sh ./vendor/bin/pest
