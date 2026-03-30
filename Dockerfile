FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache gd

RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APP_ENV=prod
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

RUN mkdir -p var/cache var/log var/sessions \
    && chown -R www-data:www-data var/ \
    && chmod -R 777 var/

EXPOSE 80