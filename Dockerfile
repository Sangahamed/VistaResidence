FROM php:8.2-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le code du projet
COPY . /var/www/html

WORKDIR /var/www/html

# Installer les dépendances Laravel
RUN composer install

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80
