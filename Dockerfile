FROM php:8.2-apache
# extensiones PDO necesarias para conectar con MySQL
RUN docker-php-ext-install pdo pdo_mysql
# AllowOverride All para los .htaccess + redirect de / a la app
RUN printf '<Directory /var/www/html>\n    AllowOverride All\n    Options -Indexes +FollowSymLinks\n</Directory>\nRedirectMatch ^/$ /Carniceria/crm/index.php\n' \
    > /etc/apache2/conf-available/ladehesa.conf \
    && a2enconf ladehesa \
    && a2enmod rewrite
# copiar la aplicación manteniendo la misma ruta que en XAMPP
COPY crm/ /var/www/html/Carniceria/crm/
RUN chown -R www-data:www-data /var/www/html/Carniceria
EXPOSE 80
