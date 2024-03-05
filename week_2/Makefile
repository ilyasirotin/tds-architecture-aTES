DOCKER_COMPOSE ?= docker compose

.env:
	cp -n .env.tmpl .env

up: .env
	$(DOCKER_COMPOSE) up -d
.PHONY: up

up-all: up
	(cd ./auth2/ && make init) && (cd ./tasks/ && make init)
.PHONY: up-all

down:
	$(DOCKER_COMPOSE) down --remove-orphans
.PHONY: down

down-clear:
	$(DOCKER_COMPOSE) down -v --remove-orphans
.PHONY: down-clear

down-clear-all:
	(cd ./auth2/ && make down-clear) && (cd ./tasks/ && make down-clear) && make down-clear
.PHONY: down-clear-all

restart: down up
.PHONY: restart
