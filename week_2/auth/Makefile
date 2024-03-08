-include .env

OAUTH_PRIVATE_KEY=./var/keys/private.key
OAUTH_PUBLIC_KEY=./var/keys/public.key

DOCKER_COMPOSE ?= docker compose

docker-prepare:
	@if [ ! -e docker-compose.override.yml ]; then \
  		cp docker-compose.override.yml.tmpl docker-compose.override.yml; \
  	fi
.PHONY: docker-prepare

doctrine-migrate:
	$(DOCKER_COMPOSE) exec php-fpm php bin/console doctrine:migrations:migrate --no-interaction
.PHONY: doctrine-migrate

oauth2-gen-keys:
	@rm -rf var/keys && mkdir var/keys
	openssl genrsa -out $(OAUTH_PRIVATE_KEY) 2048
	openssl rsa -in $(OAUTH_PRIVATE_KEY) -pubout -out $(OAUTH_PUBLIC_KEY)
.PHONY: oauth2-gen-keys

oauth2-create-clients:
	$(DOCKER_COMPOSE) exec php-fpm php bin/console league:oauth2-server:create-client \
		--redirect-uri=http://tasks.localhost/auth/callback \
		--grant-type=authorization_code --scope="email" --scope="public_id" --scope="profile" \
        tasks_auth_client
.PHONY: oauth2-create-clients

.env:
	cp -n .env.tmpl .env

up: .env docker-prepare
	$(DOCKER_COMPOSE) up -d
.PHONY: up

down:
	$(DOCKER_COMPOSE) down --remove-orphans
.PHONY: down

down-clear:
	$(DOCKER_COMPOSE) down -v --remove-orphans
.PHONY: down-clear

restart: down up
.PHONY: restart

build:
	$(DOCKER_COMPOSE) build && docker image prune --force
.PHONY: build

ssh:
	$(DOCKER_COMPOSE) exec -it php-fpm sh
.PHONY: ssh

init: build up composer-install doctrine-migrate oauth2-gen-keys oauth2-create-clients
.PHONY: init

composer-install:
	$(DOCKER_COMPOSE) run --rm php-fpm composer install
.PHONY: composer-install
