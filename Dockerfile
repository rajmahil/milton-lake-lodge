FROM wordpress:php8.1-apache



# 5. Overwrite the default wp-config with your Docker-specific config
COPY wp-config-docker.php /var/www/html/wp-config.php


# 6. Reset WORKDIR so the stock entrypoint still works
WORKDIR /var/www/html
