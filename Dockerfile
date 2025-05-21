# ──────── 1) Build stage ────────
FROM node:18 AS builder

# Work inside your theme folder
WORKDIR /app/wp-content/themes/press-wind

# Install just package.json & lockfile first (cache layer)
COPY wp-content/themes/press-wind/package*.json ./
RUN npm ci

# Copy the rest of your theme, then build
COPY wp-content/themes/press-wind ./
RUN npm run build   # <-- generates style.css (and dist/ if you use one)


# ──────── 2) Runtime stage ────────
FROM wordpress:php8.1-apache

# Copy the entire WordPress codebase (including your un‑built theme)
COPY . /var/www/html/

# Overwrite the theme with the *built* version from builder
COPY --from=builder /app/wp-content/themes/press-wind /var/www/html/wp-content/themes/press-wind

# Tweak Apache & PHP settings at build time
RUN echo 'ServerName 0.0.0.0' >> /etc/apache2/apache2.conf \
 && echo 'DirectoryIndex index.php index.html' >> /etc/apache2/apache2.conf \
 && { \
      echo 'upload_max_filesize = 50M'; \
      echo 'post_max_size = 50M'; \
    } > /usr/local/etc/php/conf.d/upload-limits.ini \
 && chown -R www-data:www-data /var/www/html
