# Use the official WordPress image with PHP & Apache
FROM wordpress:php8.1-apache

# Copy your entire WordPress project (including your built style.css)
COPY . /var/www/html/

# Tweak Apache & PHP settings at build time
RUN echo 'ServerName 0.0.0.0' \
       >> /etc/apache2/apache2.conf \
 && echo 'DirectoryIndex index.php index.html' \
       >> /etc/apache2/apache2.conf \
 && { \
      echo 'upload_max_filesize = 50M'; \
      echo 'post_max_size    = 50M'; \
    } > /usr/local/etc/php/conf.d/upload-limits.ini \
 && chown -R www-data:www-data /var/www/html

# Ensure we run Apache in the foreground (this is the default entrypoint)
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
