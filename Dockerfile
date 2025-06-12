FROM wordpress:php8.1-apache

# in CI/env you can do: --build-arg CACHEBUST=$(date +%s)
ARG CACHEBUST=1

# 1) Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2) Copy your unbuilt themes & plugins and wp-config in one shot
COPY --chown=www-data:www-data wp-content/ /var/www/html/wp-content/
COPY --chown=www-data:www-data wp-config.php   /var/www/html/wp-config.php

# 3) Bust the cache before running npm
RUN echo "cachebust=$CACHEBUST"

# 4) Build your block plugin
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 5) Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build

# 6) Reset WORKDIR so Apache’s entrypoint works
WORKDIR /var/www/html
