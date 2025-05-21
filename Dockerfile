FROM wordpress:php8.1-apache

# Copy only your wp-content (themes/plugins), let the official entrypoint pull core
COPY wp-content/ /var/www/html/wp-content

