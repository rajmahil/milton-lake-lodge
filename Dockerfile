FROM wordpress:php8.1-apache

# Copy only your wp-content (themes/plugins), let the official entrypoint pull core
COPY wp-content/ /var/www/html/wp-content


WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

