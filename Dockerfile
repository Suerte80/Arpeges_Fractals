FROM php:8.3-apache

# Installer outils nécessaires
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev && docker-php-ext-install zip

RUN docker-php-ext-install pdo pdo_mysql mysqli

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Installer PHPMailer
WORKDIR /var/www/html
# A cause du volume nommé qui écrase /var/www/html a la fin de l'execution du DockerFile il faut faire: "docker compose exec composer require phpmailer/phpmailer"
RUN composer require phpmailer/phpmailer

# Configurer Apache
RUN a2enmod rewrite
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
