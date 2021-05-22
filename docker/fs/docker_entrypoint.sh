#!/bin/bash

main() {
  [[ ! -z "$1" ]] && app_type="$1" || app_type="web"

  set_dockerhost_ip
  create_framework_directories
  clear_cache

  [ "$app_type" == "web" ] && run_web
  [ "$app_type" == "scheduler" ] && run_scheduler
  [ "$app_type" == "maintainer" ] && run_maintainer
}

set_dockerhost_ip() {
  export DOCKERHOST_IP=$(ip route | awk '/^default/ { print $3 }')
  printf "\n$DOCKERHOST_IP dockerhost\n" > /etc/hosts
  echo "dockerhost: $DOCKERHOST_IP"
}


create_framework_directories() {
  mkdir -p var/log
  mkdir -p var/cache
  mkdir -p var/uploads
}

migrate_database() {
  php bin/console --env=prod mysql:wait
  php bin/console --env=prod doctrine:migrations:migrate --no-interaction
}


clear_cache() {
  php bin/console --env=dev cache:clear
  php bin/console --env=prod cache:clear
  php bin/console --env=dev cache:clear
  php bin/console --env=prod cache:clear

  # Give access to var directory for apache user
  chmod 777 var -R
}

run_web() {
  # Start Apache
  exec apache2-foreground
}

run_scheduler() {
  migrate_database
  php bin/console --env=prod scheduler:run
}

run_maintainer() {
  # Sleep forever
  while :
  do
  	sleep 1
  done
}


main $1
