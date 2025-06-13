FROM wordpress:apache

# 1. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2. Copy the core & wp-content in one go, already owned by www-data
COPY --chown=www-data:www-data \
    --from=wordpress:apache /usr/src/wordpress/ /var/www/html/

COPY --chown=www-data:www-data \
    wp-content/ /var/www/html/wp-content/



# 4. Build your plugin
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 5. Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build


# === Add Redis object cache drop-in ===
COPY --chown=www-data:www-data \
    wp-content/plugins/redis-cache/object-cache.php \
    /var/www/html/wp-content/object-cache.php

# (Optional) Ensure ownership on all wp-content
RUN chown -R www-data:www-data /var/www/html/wp-content

WORKDIR /var/www/html
