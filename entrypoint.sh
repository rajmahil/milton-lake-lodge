#!/usr/bin/env bash
set -e

# 1) Render wp-config.php from template
envsubst '\$WORDPRESS_DB_NAME \$WORDPRESS_DB_USER \$WORDPRESS_DB_PASSWORD \$WORDPRESS_DB_HOST \$WP_REDIS_HOST \$WP_REDIS_PORT \$WP_REDIS_PASSWORD' \
  < /usr/src/wordpress/wp-config.php.tpl \
  > /var/www/html/wp-config.php

# 2) Hand off to the normal Apache entrypoint
exec docker-entrypoint.sh apache2-foreground
