# ──────────── 1) Build your theme assets ────────────
FROM node:18 AS builder

WORKDIR /app/wp-content/themes/press-wind

# Copy only package files for caching
COPY wp-content/themes/press-wind/package*.json ./
RUN npm install

# Copy theme source + build Tailwind
COPY wp-content/themes/press-wind ./
RUN npm run build    # generates style.css (and any dist/)

# ──────────── 2) Final image ────────────
FROM wordpress:php8.1-apache

# 2a) Copy your custom wp-config.php so Railway env‑vars are used
COPY wp-config.php /var/www/html/wp-config.php

# 2b) Copy your entire wp-content (so uploads/plugins/themes exist)
COPY wp-content /var/www/html/wp-content

# 2c) Overwrite just the press-wind theme with the built version
COPY --from=builder /app/wp-content/themes/press-wind \
     /var/www/html/wp-content/themes/press-wind

# 2d) Tweak Apache & PHP settings
RUN echo 'ServerName 0.0.0.0' \
       >> /etc/apache2/apache2.conf \
 && echo 'DirectoryIndex index.php index.html' \
       >> /etc/apache2/apache2.conf \
 && { echo 'upload_max_filesize = 50M'; echo 'post_max_size = 50M'; } \
       > /usr/local/etc/php/conf.d/upload-limits.ini \
 && chown -R www-data:www-data /var/www/html

# Let the official entrypoint run
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
