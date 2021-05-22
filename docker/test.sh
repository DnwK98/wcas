#!/bin/bash
test -f "docker/test.sh" || { echo "[ERROR] Run script only from project context"; exit 1; }

set -a
source .env.dist
source .env.test

docker-compose -f docker/docker-compose.test.yaml up \
  --abort-on-container-exit \
  --exit-code-from wcas-test
