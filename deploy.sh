#!/bin/sh
# activate maintenance mode
# php artisan down

# update source code
git pull origin main --rebase
php artisan opt:clear
# php artisan migrate:fresh --seed

# update PHP dependencies
#composer install --no-interaction --prefer-dist
# composer install
# --no-interaction Do not ask any interactive question
# --no-dev  Disables installation of require-dev packages.
# --prefer-dist  Forces installation from package dist even for dev versions.

# clear config cache
# composer app:clear

# run autoload
# composer dump-autoload

# update database
# php artisan migrate --force
# --force  Required to run when in production.

# clear cache
# php artisan optimize:clear

# clear config cache
# composer app:clear

# run autoload
# composer dump-autoload

# stop maintenance mode
# php artisan up
