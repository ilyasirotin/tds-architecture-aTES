-include .env

OAUTH_PRIVATE_KEY=./var/keys/private.key
OAUTH_PUBLIC_KEY=./var/keys/public.key

DOCKER_COMPOSE ?= docker compose

docker-prepare:
	@if [ ! -e docker-compose.override.yml ]; then \
  		cp docker-compose.override.yml.tmpl docker-compose.override.yml; \
  	fi
.PHONY: docker-prepare

gen-keys:
	@rm -rf var/keys && mkdir var/keys
	openssl genrsa -aes128 -passout pass:$(OAUTH_PASSPHRASE) -out $(OAUTH_PRIVATE_KEY) 2048
	openssl rsa -in $(OAUTH_PRIVATE_KEY) -passin pass:$(OAUTH_PASSPHRASE) -pubout -out $(OAUTH_PUBLIC_KEY)
.PHONY: gen-keys

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

init: build up composer-install gen-keys
.PHONY: init

composer-install:
	$(DOCKER_COMPOSE) run --rm php-fpm composer install
.PHONY: composer-install
