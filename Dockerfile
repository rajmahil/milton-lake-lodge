FROM wordpress:apache

# 1. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2. Pre-seed the webroot with WordPress core from the official image
COPY --from=wordpress:apache /usr/src/wordpress/ /var/www/html/

# 3. Drop in your wp-config and custom wp-content
COPY wp-config.php       /var/www/html/wp-config.php
COPY wp-content/         /var/www/html/wp-content/

# 4. Build your plugin & theme
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# 5. Fix perms & reset WORKDIR
RUN chown -R www-data:www-data /var/www/html/wp-content \
    && find /var/www/html/wp-content -type d -exec chmod 775 {} \; \
    && find /var/www/html/wp-content -type f -exec chmod 664 {} \;

WORKDIR /var/www/html
