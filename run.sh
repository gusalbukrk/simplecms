#!/bin/bash

# error if script received no or more than 1 argument
if [[ $# != 1 ]]; then
  echo "ERROR: Script must receive exactly 1 argument — MySQL user root password."
  exit 1
fi

# error if argument doesn't meet the requirements
if ! [[
  ${#1} -ge 8 &&
  $1 =~ [a-z] &&
  $1 =~ [A-Z] &&
  $1 =~ [0-9] &&
  $1 =~ [[:punct:]]
]]; then
  echo "ERROR: password minimum length is 8 and it must contain at least 1 uppercase letter, 1 lowercase letter, 1 number and 1 punctuation mark."
  exit 1
fi

# create password file using argument supplied to the script
echo -n $1 > db_root_password.txt

# PHP must be installed to run composer
# usual installation (`sudo apt install php`) has apache as dependency
# installing apache would not only be pointless but would also take up port 80
# https://askubuntu.com/a/1357414
sudo apt install php-fpm php-cli zip unzip php-zip # install php without apache

# install PHP dependencies
cd www
php composer.phar install
cd ..

# create and start containers
docker compose up -d

# by default volume owner is root, change it to current user
sudo chown -R "$USER":"$USER" logs/
