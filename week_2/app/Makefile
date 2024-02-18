-include .env

DOCKER_COMPOSE ?= docker compose

.prepare:
	cp -n docker-compose.override.yml.tmpl docker-compose.override.yml

.env:
	cp -n .env.tmpl .env

up: .env .prepare
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

init: build up composer-install
.PHONY: init

composer-install:
	$(DOCKER_COMPOSE) run --rm php-fpm composer install
.PHONY: composer-install
