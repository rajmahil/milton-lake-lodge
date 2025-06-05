FROM wordpress:php8.1-apache

# Install Node.js
# RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
#     && apt-get install -y nodejs

# Copy wp-content (themes/plugins only!)
COPY wp-content/ /var/www/html/wp-content

# # Build first plugin
# WORKDIR /var/www/html/wp-content/plugins/my-first-block
# RUN npm ci && npm run build

# # Build theme
# WORKDIR /var/www/html/wp-content/themes/theme-tailwind
# RUN npm ci && npm run build

# FINAL — reset WORKDIR so entrypoint works!
WORKDIR /var/www/html
