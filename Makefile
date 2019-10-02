up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up game-init
test: game-test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

game-init: game-composer-install game-migrations

game-composer-install:
	docker-compose run --rm game-php-cli composer install

game-test:
	docker-compose run --rm game-php-cli php bin/phpunit

game-migrations:
	docker-compose run --rm game-php-cli php bin/console doctrine:migrations:migrate --no-interaction

game-fixtures:
	docker-compose run --rm game-php-cli php bin/console doctrine:fixtures:load --no-interaction
