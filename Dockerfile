# 0. Allow this to float to the image tagged with the newest PHP version
ARG BASE_IMAGE=wordpress:apache

FROM ${BASE_IMAGE} AS base

# 1. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2. Copy the WordPress core into the webroot at build-time
COPY --from=base /usr/src/wordpress/ /var/www/html/

# 3. Drop in your custom config
COPY wp-config.php /var/www/html/wp-config.php

# 4. Overlay your themes & plugins
COPY wp-content/ /var/www/html/wp-content/

# 5. Build your plugin (if it lives in wp-content/plugins)
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 6. Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# 7. Fix ownership & permissions so Apache can serve everything
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# 8. Reset back to webroot for the entrypoint
WORKDIR /var/www/html
