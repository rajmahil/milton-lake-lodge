FROM wordpress:php8.1-apache

# copy only your theme (or whole wp-content if needed)
COPY wp-content/themes/tailpress-wp \
     /var/www/html/wp-content/themes/tailpress-wp

# fix permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755             /var/www/html
