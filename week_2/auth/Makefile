-include .env

DOCKER_COMPOSE ?= docker compose

docker-prepare:
	@if [ ! -e docker-compose.override.yml ]; then \
  		cp docker-compose.override.yml.tmpl docker-compose.override.yml; \
  	fi
.PHONY: docker-prepare

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

init: build up composer-install
.PHONY: init

composer-install:
	$(DOCKER_COMPOSE) run --rm php-fpm composer install
.PHONY: composer-install
