.DEFAULT_GOAL := help

## GENERAL ##
OWNER               = miguel-saenz
SERVICE_NAME        = apirest-upload
USERNAME_LOCAL      ?= "$(shell whoami)"
UID_LOCAL           ?= "$(shell id -u)"
GID_LOCAL           ?= "$(shell id -g)"

## DEV ##
DOCKER_NETWORK      = services_network
TAG_CLI             = cli
TAG_DEV             = dev

## DEPLOY ##
ENV                 ?= dev
CYBORG_BUCKET		?= project-cyborg.${ENV}
DEPLOY_REGION		?= sa-east-1

## RESULT VARS ##
PROJECT_NAME        = ${OWNER}-${ENV}-${SERVICE_NAME}
CONTAINER_NAME      = ${PROJECT_NAME}_backend
IMAGE_CLI           = ${PROJECT_NAME}:${TAG_CLI}
IMAGE_DEV           = ${PROJECT_NAME}:${TAG_DEV}

COMMAND             ?= composer update --ignore-platform-reqs

## CUSTOM VARS ##
PROJECT_DOMAIN		= upload.test


build: ## make build
	docker build -f docker/cli/Dockerfile \
						--build-arg USERNAME_LOCAL=$(USERNAME_LOCAL) \
						--build-arg UID_LOCAL=$(UID_LOCAL) \
						--build-arg GID_LOCAL=$(GID_LOCAL) \
						-t ${IMAGE_CLI} docker/cli

	docker build -f docker/latest/Dockerfile -t ${IMAGE_DEV} docker/latest

up: ## start up the container
	@make verify_network &> /dev/null
	@IMAGE_DEV=${IMAGE_DEV} \
	CONTAINER_NAME=${CONTAINER_NAME} \
	DOCKER_NETWORK=${DOCKER_NETWORK} \
	PROJECT_DOMAIN=${PROJECT_DOMAIN} \
	docker-compose -p ${SERVICE_NAME} up -d backend
	@make add_local_domain HOST_NAME=${PROJECT_DOMAIN}
	@make status

stop: ## stop the container
	@IMAGE_DEV=${IMAGE_DEV} \
	CONTAINER_NAME=${CONTAINER_NAME} \
	DOCKER_NETWORK=${DOCKER_NETWORK} \
	PROJECT_DOMAIN=${PROJECT_DOMAIN} \
	docker-compose -p ${SERVICE_NAME} stop

down:
	@IMAGE_DEV=${IMAGE_DEV} \
	CONTAINER_NAME=${CONTAINER_NAME} \
	DOCKER_NETWORK=${DOCKER_NETWORK} \
	PROJECT_DOMAIN=${PROJECT_DOMAIN} \
	docker-compose -p ${SERVICE_NAME} down

command: ## execute some command
	docker run --rm -u ${UID_LOCAL}:${GID_LOCAL} -t \
				--net $(DOCKER_NETWORK) \
				-v $$PWD/src:/app \
				-v $$HOME/.ssh:/home/${USERNAME_LOCAL}/.ssh \
				${IMAGE_CLI} ${COMMAND}

composer: ## install dependencies from composer.json
	@make command COMMAND="composer update --ignore-platform-reqs"

ssh: ## bash
	DOCKER_NETWORK=${DOCKER_NETWORK} \
    	docker exec -it ${CONTAINER_NAME} bash

status:
	DOCKER_NETWORK=${DOCKER_NETWORK} \
	docker-compose -p ${SERVICE_NAME} ps

logs:
	DOCKER_NETWORK=${DOCKER_NETWORK} \
	docker-compose -p ${SERVICE_NAME} logs -f

verify_network:
	@if [ -z $$(docker network ls | grep ${DOCKER_NETWORK} | awk '{print $$2}') ]; then\
	    (docker network create ${DOCKER_NETWORK});\
	fi

add_local_domain:
	@if [ -z "${HOST_NAME}" ]; then (echo "Please set the ip in to 'HOST_NAME' variable. e.g. HOST_NAME=local.sample.test" && exit 1); fi
	$(eval ETC_HOSTS := /etc/hosts)
	$(eval IP := 127.0.0.1)
	$(eval HOSTS_LINE := '$(IP)\t$(HOST_NAME)')
	@if [ -n "$$(grep $(HOST_NAME) /etc/hosts)" ]; \
		then \
			echo "$(HOST_NAME) already exists : $$(grep $(HOST_NAME) $(ETC_HOSTS))"; \
		else \
			echo "Adding $(HOST_NAME) to your $(ETC_HOSTS)"; \
			sudo -- sh -c -e "echo $(HOSTS_LINE) >> /etc/hosts"; \
			if [ -n "$$(grep $(HOST_NAME) /etc/hosts)" ];\
				then \
					echo "$(HOST_NAME) was added succesfully \n $$(grep $(HOST_NAME) /etc/hosts)"; \
				else \
					echo "Failed to Add $(HOST_NAME), Try again!"; \
			fi \
	fi

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'
