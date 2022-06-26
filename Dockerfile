FROM php:7.4.30-apache
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt-get update && docker-php-ext-install pdo_mysql
RUN apt-get install -y git
RUN composer require "twig/twig:^3.0"
