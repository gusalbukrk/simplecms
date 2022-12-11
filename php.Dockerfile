FROM php:8.2.0-apache
RUN a2enmod ssl rewrite
RUN docker-php-ext-install pdo pdo_mysql
