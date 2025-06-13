FROM wordpress:php8.1-apache

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Copy and build your plugins and theme
COPY wp-content/ /var/www/html/wp-content/
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# Copy the template and entrypoint
COPY wp-config.php.tpl /usr/src/wordpress/wp-config.php.tpl
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use our entrypoint instead of the stock one
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
