# Use the official WordPress image with Apache & PHP
FROM wordpress:php8.1-apache

# Copy your entire site into the container
COPY . /var/www/html
 
# (Optional) If you want uploads to persist, you can mount /var/www/html/wp-content/uploads as a volume at runtime.
