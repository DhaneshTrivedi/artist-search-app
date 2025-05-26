# Use PHP-Apache base image
FROM php:8.2-apache

# Enable mod_rewrite for Apache (if needed)
RUN a2enmod rewrite

# Copy project files into container
COPY . /var/www/html/

# Install MySQL extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html/

# Expose default web port
EXPOSE 80
