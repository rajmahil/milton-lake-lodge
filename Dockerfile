FROM wordpress:php8.1-apache

# 1) Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2) Copy wp-content (themes & plugins) into the image
COPY wp-content/ /var/www/html/wp-content/

# 3) Build your block plugin
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 4) Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# 5) Copy in wp-config (so your credentials end up in the image)
COPY wp-config.php /var/www/html/wp-config.php

# 6) Reset to webroot so Apache’s entrypoint works
WORKDIR /var/www/html
