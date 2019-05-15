#!/usr/bin/env bash

echo "Install new dependencies";
composer install

echo "Apply migrations";
php bin/console doctrine:migrations:migrate -n

echo "Clear production cache";
php bin/console cache:clear -e prod

echo "Remove global README.md";
rm ./README.md
echo "Copy the server ReadMe.md";
cp ./devops/ReadMe.md .
