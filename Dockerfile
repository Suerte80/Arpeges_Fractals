FROM php:8.3-apache

# Installer outils nécessaires
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev && docker-php-ext-install zip

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Installer PHPMailer
WORKDIR /var/www/html
RUN composer require phpmailer/phpmailer
