#!/bin/bash
test -f "docker/stop.sh" || { echo "[ERROR] Run script only from project context"; exit 1; }

test -f ".env.docker" || {
  echo "[INFO] Missing .env.docker file. Creating from .env.dist"
  cp .env.dist .env.docker
}

set -a
source .env.docker

docker-compose -f docker/docker-compose.yaml down
