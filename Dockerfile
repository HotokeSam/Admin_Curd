FROM php:8.2-apache

RUN docker-php-ext-install mysqli

WORKDIR /var/www/html

COPY . .