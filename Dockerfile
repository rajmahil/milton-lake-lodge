FROM wordpress:php8.1-apache

# copy only your theme (or whole wp-content if needed)
COPY wp-content/themes/press-wind \
     /var/www/html/wp-content/themes/press-wind

# fix permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755             /var/www/html
