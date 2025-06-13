FROM wordpress:php8.1-apache

# 1. Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# 2. Copy only your themes & plugins
COPY wp-content/ /var/www/html/wp-content/

# 3. Build your custom plugin
WORKDIR /var/www/html/wp-content/plugins/my-first-block
RUN npm ci && npm run build

# 4. Build your Tailwind theme
WORKDIR /var/www/html/wp-content/themes/theme-tailwind
RUN npm ci && npm run build


# 6. Reset WORKDIR so the stock entrypoint still works
WORKDIR /var/www/html
