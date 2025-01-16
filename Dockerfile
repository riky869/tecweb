FROM php:8.1-apache
RUN docker-php-ext-install mysqli

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data