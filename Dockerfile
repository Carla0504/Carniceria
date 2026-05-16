FROM php:8.2-apache

# extensiones PDO necesarias para conectar con MySQL
RUN docker-php-ext-install pdo pdo_mysql

# copiar la aplicación manteniendo la misma ruta que en XAMPP
COPY crm/ /var/www/html/Carniceria/crm/

RUN chown -R www-data:www-data /var/www/html/Carniceria

EXPOSE 80
