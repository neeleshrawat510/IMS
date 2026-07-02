FROM php:8.2-apache

# Install PHP MySQL support
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Disable conflicting MPM modules (FIX)
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Enable rewrite (optional but useful)
RUN a2enmod rewrite

COPY . /var/www/html/

EXPOSE 80