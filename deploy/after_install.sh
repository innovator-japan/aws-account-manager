#!/bin/bash

CURRENT=/srv/www/aws.innovator-jp.info
SHARED=/srv/www/shared
cd $CURRENT

ln -sf $SHARED/.env $CURRENT/.env
php $CURRENT/artisan migrate --force
php $CURRENT/artisan optimize
