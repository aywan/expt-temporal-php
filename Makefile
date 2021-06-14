-include .env

# confs
USER_ID?=$(shell id -u)
GROUP_ID?=$(shell id -g)

# aliases
DC?=docker-compose

.EXPORT_ALL_VARIABLES:

.PHONY: build
build:
	${DC} build

.PHONY: up
up:
	${DC} up -d

.PHONY: php-shell
php-shell:
	${DC} run --rm php-cli bash
