FROM php:8.1-apache

# Enable mod_rewrite (optional, good for pretty URLs)
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Set the document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update the Apache config with the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy the full project
COPY . /var/www/html

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Expose the default Apache port
EXPOSE 80
