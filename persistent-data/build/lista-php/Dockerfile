# Imagen base con Apache y PHP
FROM php:8.2-apache

# Instalar extensiones necesarias para MariaDB
RUN docker-php-ext-install pdo pdo_mysql

# Instalar dependencias para SQL INJECTION
RUN docker-php-ext-install mysqli


# Establecer permisos
RUN chown -R www-data:www-data /var/www/html

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

