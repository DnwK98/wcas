#!/bin/bash
test -f "docker/run.sh" || { echo "[ERROR] Run script only from project context"; exit 1; }

test -f ".env.docker" || {
  echo "[INFO] Missing .env.docker file. Creating from .env.dist"
  cp .env.dist .env.docker
}

mkdir -p docker/var/mysql_data

set -a
source .env.docker

docker-compose -f docker/docker-compose.yaml up -d
docker-compose -f docker/docker-compose.yaml logs -f
