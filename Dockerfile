FROM wordpress:apache

# 1. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*


# 2. Copy WP core + your plugins/themes in one shot
COPY --chown=www-data:www-data \
    --chmod=Du=rwx,Dg=rwx,Do=rx,Fu=rw,Fg=rw,Fo=r \
    --from=wordpress:apache /usr/src/wordpress/ /var/www/html/
COPY --chown=www-data:www-data \
    --chmod=Du=rwx,Dg=rwx,Do=rx,Fu=rw,Fg=rw,Fo=r \
    wp-content/ /var/www/html/wp-content/


# 4. Build your custom plugin
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 5. Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build


# 6. Final ownership/permissions (in case anything changed)
RUN chown -R www-data:www-data /var/www/html/wp-content \
    && chmod -R ug+rwX           /var/www/html/wp-content


# 7. Reset back to webroot for the official entrypoint
WORKDIR /var/www/html
