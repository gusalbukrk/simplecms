#!/bin/bash

# script must receive exactly 2 arguments, otherwise error
if [[ $# != 2 ]]; then
  echo "ERROR: Script must receive exactly 2 arguments — root & admin passwords."
  exit 1
fi

# create password files usign the arguments
echo $1 > db_root_password.txt
echo $2 > db_password.txt

# install PHP dependencies
cd www
php composer.phar install
cd ..

# create and start containers
docker compose up -d

# by default volume owner is root, change it to current user
sudo chown -R "$USER":"$USER" logs/
