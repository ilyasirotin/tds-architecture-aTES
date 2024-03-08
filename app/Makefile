-include .env
-include ./auth/.env
-include ./tasks/.env

DOCKER_COMPOSE ?= docker compose

OAUTH_KEYS_ROOT=./auth/var/keys
OAUTH_PRIVATE_KEY=./auth/var/keys/private.key
OAUTH_PUBLIC_KEY=./auth/var/keys/public.key

.env:
	cp -n .env.tmpl .env &&\
	cp -n ./auth/.env.tmpl ./auth/.env &&\
	cp -n ./tasks/.env.tmpl ./tasks/.env

doctrine-migrate:
	$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
	$(DOCKER_COMPOSE) exec tasks-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
.PHONY: doctrine-migrate

oauth2-gen-keys:
	@rm -rf $(OAUTH_KEYS_ROOT) && mkdir $(OAUTH_KEYS_ROOT)
	openssl genrsa -out $(OAUTH_PRIVATE_KEY) 2048
	openssl rsa -in $(OAUTH_PRIVATE_KEY) -pubout -out $(OAUTH_PUBLIC_KEY)
.PHONY: oauth2-gen-keys

oauth2-create-clients:
	$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console league:oauth2-server:create-client \
		--redirect-uri=http://localhost:8081 \
		--grant-type=authorization_code --scope='profile' \
        tasks_auth_client
.PHONY: oauth2-create-clients

fixtures-load:
	$(DOCKER_COMPOSE) exec auth-php-fpm php bin/console doctrine:fixtures:load --append
.PHONY: fixtures-load

composer-install:
	$(DOCKER_COMPOSE) run --rm auth-php-fpm composer install
	$(DOCKER_COMPOSE) run --rm tasks-php-fpm composer install
.PHONY: composer-install

build:
	$(DOCKER_COMPOSE) build && docker image prune --force
.PHONY: build

up: .env
	$(DOCKER_COMPOSE) up -d
.PHONY: up

down:
	$(DOCKER_COMPOSE) down --remove-orphans
.PHONY: down

down-clear:
	$(DOCKER_COMPOSE) down -v --remove-orphans
.PHONY: down-clear

init: build up composer-install doctrine-migrate fixtures-load oauth2-gen-keys oauth2-create-clients
.PHONY: init

ssh-auth:
	$(DOCKER_COMPOSE) exec -it auth-php-fpm sh
.PHONY: ssh-auth

ssh-tasks:
	$(DOCKER_COMPOSE) exec -it tasks-php-fpm sh
.PHONY: ssh-tasks
