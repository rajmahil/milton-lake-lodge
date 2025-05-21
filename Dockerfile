FROM wordpress:php8.1-apache

# 1) Copy just your theme
COPY wp-content/themes/press-wind \
     /var/www/html/wp-content/themes/press-wind

# 2) (Optional) Persist uploads
VOLUME /var/www/html/wp-content/uploads

# 3) Fix ownership/permissions so Apache can read everything
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755             /var/www/html
