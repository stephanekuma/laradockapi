# Base image
FROM php:8.3-cli

# Install required dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    zlib1g-dev \
    libicu-dev \
    g++ \
    supervisor \
    cron

# Install PHP Redis extensions
RUN pecl install redis && docker-php-ext-enable redis

# Configure PHP extensions
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Copy custom php.ini
# COPY php.ini /usr/local/etc/php/php.ini

# Set up Supervisor configuration
COPY supervisord.conf /etc/supervisor/supervisord.conf

# Set up Supervisor log directory
RUN mkdir -p /var/log/supervisor && \
    touch /var/log/supervisor/supervisord.log /var/log/supervisor/cron.log /var/log/supervisor/horizon.log /var/log/supervisor/reverb.log

# Ensure log permissions
RUN chmod -R 777 /var/log/supervisor

# Set up Cron
COPY cron.d/schedule /etc/cron.d/schedule
RUN chmod 644 /etc/cron.d/schedule && crontab /etc/cron.d/schedule

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Default working directory
WORKDIR /etc/supervisor/conf.d/

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
