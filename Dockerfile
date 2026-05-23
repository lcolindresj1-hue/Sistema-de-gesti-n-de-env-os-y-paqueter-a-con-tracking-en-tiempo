FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli \
    && a2enmod rewrite

RUN echo "Listen 8080" > /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 8080

CMD ["apache2-foreground"]
