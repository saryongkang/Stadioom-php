#!/bin/sh

cd ~

if [ -f dev.pem ];
then

BASE_DIR=/Applications/MAMP/htdocs/Stadioom-php

cp $BASE_DIR/index_prod.php $BASE_DIR/index.php
cp $BASE_DIR/application/doctrine-cli_prod.php $BASE_DIR/application/doctrine-cli.php
cp $BASE_DIR/application/config/config_prod.php $BASE_DIR/application/config/config.php
cp $BASE_DIR/application/config/database_prod.php $BASE_DIR/application/config/database.php
cp $BASE_DIR/application/config/facebook_prod.php $BASE_DIR/application/config/facebook.php

rsync -Paz --rsh "ssh -i ./dev.pem" --rsync-path "sudo rsync" --exclude 'user_guide' --exclude '*.git' $BASE_DIR/ ubuntu@107.20.209.139:/var/www/

cp $BASE_DIR/index_dev.php $BASE_DIR/index.php
cp $BASE_DIR/application/doctrine-cli_dev.php $BASE_DIR/application/doctrine-cli.php
cp $BASE_DIR/application/config/config_dev.php $BASE_DIR/application/config/config.php
cp $BASE_DIR/application/config/database_dev.php $BASE_DIR/application/config/database.php
cp $BASE_DIR/application/config/facebook_dev.php $BASE_DIR/application/config/facebook.php

else

echo "ERROR: The dev.pem file should be placed at your HOME directory."
cd -
exit 1

fi

cd -
exit 0
