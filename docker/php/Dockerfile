FROM php:7.4-apache

# Installation des extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql exif

# Activation du module rewrite d'Apache
RUN a2enmod rewrite

# Copie de la configuration PHP personnalisée (upload size, etc.)
COPY php-config.ini /usr/local/etc/php/conf.d/php-config.ini

WORKDIR /var/www/html