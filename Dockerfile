# Use an official PHP image with Apache pre-installed
FROM php:8.2-apache

# Install system dependencies and PHP extensions required by CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli gd zip \
    && a2enmod rewrite

# Point Apache's Document Root directly to CodeIgniter's public folder for security
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy the entire workspace into the container
WORKDIR /var/www/html
COPY . /var/www/html

# Grant read/write permissions to CodeIgniter's writable storage partitions
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# Install Composer automatically to build system dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Expose web port 80
EXPOSE 80