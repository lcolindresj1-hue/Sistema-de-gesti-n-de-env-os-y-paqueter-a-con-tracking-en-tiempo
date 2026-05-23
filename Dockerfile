FROM php:8.2-apache

RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_worker.load \
          /etc/apache2/mods-enabled/mpm_prefork.load \
    && a2enmod mpm_prefork \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod rewrite

RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 8080
