-include .env

# confs
USER_ID?=$(shell id -u)
GROUP_ID?=$(shell id -g)
DOMAIN=temporal.docker

# aliases
DC?=docker-compose

.EXPORT_ALL_VARIABLES:

.PHONY: build
build:
	${DC} build

.PHONY: up
up:
	${DC} up -d --remove-orphans

.PHONY: down
down:
	${DC} down --remove-orphans

.PHONY: php-shell
php-shell:
	${DC} run --rm php-cli bash

.PHONY: rr-reload
rr-reload:
	${DC} exec rr ./rr reset
