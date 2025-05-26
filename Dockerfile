FROM php:8.1-apache

# Enable necessary Apache modules
RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www/html

# Set the document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Copy the entire project
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Configure Apache to serve the api/ directory
RUN echo "Alias /api /var/www/html/api\n\
<Directory /var/www/html/api>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride None\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/api.conf \
    && a2enconf api

# Expose port 80
EXPOSE 80


# FROM php:8.1-apache

# # Enable mod_rewrite
# RUN a2enmod rewrite

# # Set working directory
# WORKDIR /var/www/html

# # Set custom document root to public/
# ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# # Update Apache config for document root
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
#     && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# # Copy all project files
# COPY . /var/www/html

# # 🔥 Fix permissions — this is the key!
# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 755 /var/www/html

# # Install MySQLi extension
# RUN docker-php-ext-install mysqli

# # Expose default port
# EXPOSE 80
