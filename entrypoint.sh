#!/bin/sh

docker-php-ext-install pdo_mysql
exec php /project/config.php $*