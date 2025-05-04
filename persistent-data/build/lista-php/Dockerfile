# Imagen base con Apache y PHP
FROM php:8.2-apache

# Instalar extensiones necesarias para MariaDB
RUN docker-php-ext-install pdo pdo_mysql


# Establecer permisos
RUN chown -R www-data:www-data /var/www/html
