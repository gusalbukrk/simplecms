FROM php:7.4.33-apache
RUN a2enmod ssl rewrite
RUN docker-php-ext-install pdo pdo_mysql
