#!/bin/sh

php -m | grep pdo_mysql || docker-php-ext-install pdo_mysql

exec php /project/config.php $*