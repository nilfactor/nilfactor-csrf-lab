FROM php:7-apache

COPY code/ /var/www/html
COPY config/ /etc/apache2/sites-enabled/

RUN chmod -Rf 755 /var/www/html/
RUN a2enmod headers

EXPOSE 80
CMD ["apache2-foreground"]