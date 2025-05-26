FROM php:8.1-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Set custom document root to public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache config for document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy all project files
COPY . /var/www/html

# 🔥 Fix permissions — this is the key!
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Expose default port
EXPOSE 80
