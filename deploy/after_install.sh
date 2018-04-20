#!/bin/bash

CURRENT=/srv/www/account-manager
SHARED=/srv/www/shared
cd $CURRENT

ln -sf $SHARED/.env $CURRENT/.env
php $CURRENT/artisan migrate --force
php $CURRENT/artisan optimize
