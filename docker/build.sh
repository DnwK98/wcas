#!/bin/bash
test -f "docker/build.sh" || { echo "[ERROR] Run script only from project context"; exit 1; }

docker build -f docker/Dockerfile -t wcas-local . || exit 1
