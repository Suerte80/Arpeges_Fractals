FROM php:8.3-apache

# Installer outils nécessaires
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev gnupg2 ca-certificates \
    && docker-php-ext-install zip

RUN docker-php-ext-install pdo pdo_mysql mysqli

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Installer NodeJS
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# Installer PHPMailer
WORKDIR /var/www/html
# A cause du volume nommé qui écrase /var/www/html a la fin de l'execution du DockerFile il faut faire: "docker compose exec composer require phpmailer/phpmailer"
RUN composer require phpmailer/phpmailer

# Configurer Apache
RUN a2enmod rewrite
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
