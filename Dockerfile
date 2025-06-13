FROM wordpress:php8.1-apache

# Install Node.js + envsubst
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs gettext-base \
    && rm -rf /var/lib/apt/lists/*

# Copy & build WP themes/plugins
COPY wp-content/ /var/www/html/wp-content/
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# Copy in the wp-config template and our wrapper
COPY wp-config.php.tpl /usr/src/wordpress/wp-config.php.tpl
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use our wrapper instead of the stock entrypoint
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
