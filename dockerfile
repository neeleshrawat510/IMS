FROM php:8.2-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Force clean Apache MPM state
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2dismod mpm_prefork || true
RUN a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

# Copy app
COPY . /var/www/html/

EXPOSE 80