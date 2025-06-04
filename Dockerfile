FROM wordpress:php8.1-apache

# Copy only your wp-content (themes/plugins), let the official entrypoint pull core
COPY wp-content/ /var/www/html/wp-content


RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build