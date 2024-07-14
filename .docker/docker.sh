#!/bin/bash
composer install

php bin/console doctrine:s:c --no-interaction

echo "Application ready to use"

exec apache2-foreground