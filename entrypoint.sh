#!/usr/bin/env bash
set -e

# 1) Let the official WP entrypoint do its job (copy core, set perms, etc.)
exec docker-entrypoint.sh "$@" &

# 2) Wait until WordPress core is in place
while [ ! -f /var/www/html/wp-settings.php ]; do
  sleep 1
done

# 3) Render the real wp-config.php from the template
envsubst '\
$WORDPRESS_DB_NAME $WORDPRESS_DB_USER $WORDPRESS_DB_PASSWORD $WORDPRESS_DB_HOST \
$WP_REDIS_HOST $WP_REDIS_PORT $WP_REDIS_PASSWORD' \
< /usr/src/wordpress/wp-config.php.tpl \
> /var/www/html/wp-config.php

# 4) Now wait on Apache to exit
wait
