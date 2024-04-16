# Use the official PHP 8.1 Apache base image
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libzip-dev \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libpq-dev \
    libxml2-dev \
    librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip intl xml opcache

# Enable Apache modules
RUN a2enmod rewrite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Remove the default index.html
RUN rm -rf /var/www/html/index.html

# Copy Symfony files
COPY . .

# Install Symfony dependencies
RUN export COMPOSER_ALLOW_SUPERUSER=1 && composer install --no-scripts

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
