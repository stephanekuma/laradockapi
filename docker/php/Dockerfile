# Base image
FROM php:8.3-fpm

# Arguments defined in compose.yaml
ARG user
ARG uid

# Starting from scratch
RUN apt-get clean && apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Human Language and Character Encoding Support
RUN apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Clean up
RUN apt-get clean && apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP MySQL extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install PHP Redis extensions
RUN pecl install redis && docker-php-ext-enable redis

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -u $uid -ms /bin/bash -g www-data $user

# Set working directory
COPY . /var/www

# Copy existing application directory contents
COPY --chown=$user:www-data . /var/www

# Switch to non-root user
USER $user

# Expose port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
