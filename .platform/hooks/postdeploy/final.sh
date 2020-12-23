#!/bin/bash
cd /var/www/html
rm -f .env.local
mv .env.local.prod .env.local
sudo /usr/bin/composer.phar install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 /usr/bin/php bin/console cache:clear --env=prod