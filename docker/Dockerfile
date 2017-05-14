# Create a base Ubuntu image to build upon
FROM ubuntu:16.04
MAINTAINER Richard Morgan <r_morgan@sympatico.ca>

VOLUME ["/var/www"]
WORKDIR /var/www

# set terminal variable so that tools such as nano and htop work
# this is not needed in prod containers, but is useful for devs
ENV TERM=xterm

EXPOSE 9000

COPY ./docker/docker-entrypoint.sh /docker-entrypoint.sh
ENTRYPOINT /docker-entrypoint.sh

RUN apt-get update && apt-get install -y \
    curl nano sed libwww-perl htop ca-certificates \
    php7.0 php-cli php-fpm php-curl php-pear php-mcrypt php-zip php-mbstring php-xml \
    php-pgsql php-redis \
    php-xdebug \
  && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
  && echo "xdebug.remote_enable=on" >> /etc/php/7.0/mods-available/xdebug.ini \
  && echo "xdebug.remote_autostart=on" >> /etc/php/7.0/mods-available/xdebug.ini \
  && echo "xdebug.remote_port = 9001" >> /etc/php/7.0/mods-available/xdebug.ini \
  && echo "xdebug.max_nesting_level=300" >> /etc/php/7.0/mods-available/xdebug.ini \
  && echo "xdebug.remote_connect_back=1" >> /etc/php/7.0/mods-available/xdebug.ini

COPY ./ /var/www/
COPY ./docker/docker-php-fpm.conf /etc/php/7.0/fpm/pool.d/www.conf

