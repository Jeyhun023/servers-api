#!/bin/sh
set -e

# Install dependencies
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-ansi --no-interaction --no-progress --optimize-autoloader
fi

if [ ! -f ".env.local" ]; then
    cp .env.local .env
fi

mkdir -p var/cache var/log

exec php-fpm
