FROM wordpress:apache

# 1. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2. Seed in WordPress core & your custom content (owned by www-data)
COPY --chown=www-data:www-data \
    --from=wordpress:apache /usr/src/wordpress/ /var/www/html/
COPY --chown=www-data:www-data \
    wp-content/ /var/www/html/wp-content/
COPY php.ini /usr/local/etc/php/conf.d/custom-php.ini



# 4. Build your custom plugin
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 5. Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# 6. Now fix ownership & permissions on everything under wp-content
RUN chown -R www-data:www-data /var/www/html/wp-content \
    && chmod -R ug+rwX /var/www/html/wp-content

# 7. Reset back to webroot for the official entrypoint
WORKDIR /var/www/html
