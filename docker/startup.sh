#!/bin/sh

sed -i "s,LISTEN_PORT,$PORT,g" /etc/nginx/nginx.conf

php-fpm -D

/usr/bin/supervisord -c /app/docker/supervisord.conf

nginx
