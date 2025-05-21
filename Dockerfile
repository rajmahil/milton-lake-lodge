# ──────── 1) Build stage ────────
FROM node:18 AS builder

# Work inside your theme folder
WORKDIR /app/wp-content/themes/press-wind

# Copy only package.json (and package-lock.json if you ever add one)
COPY wp-content/themes/press-wind/package.json ./

# Install dependencies (including devDeps for the build)
RUN npm install

# Copy the rest of the theme files and build
COPY wp-content/themes/press-wind ./
RUN npm run build   # creates style.css and/or dist/


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
