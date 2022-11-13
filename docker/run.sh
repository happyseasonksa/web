#!/bin/sh

cd /var/www
pwd
ls -la
echo "TEXTXTXTXTXTXTXXTXT"

composer install
# php artisan migrate:fresh --seed
#php artisan cache:clear
#php artisan route:cache

php artisan key:generate
