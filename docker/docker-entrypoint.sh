#!/usr/bin/env bash

# start required services
#service ssh start
#service php5-fpm start

# keep the container running
#tail -f /dev/null
#tail -f /var/www/storage/logs/lumen.log
/usr/sbin/php-fpm7.0 -F

