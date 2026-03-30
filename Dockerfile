FROM php:8.2-apache

# 1. Installation des dépendances système et drivers PostgreSQL
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libpq-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache gd

# 2. Configuration Apache (Gestion du /public et des réécritures d'URL)
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Autoriser explicitement le .htaccess dans le dossier public
RUN echo "<Directory /var/www/html/public>\n\tOptions Indexes FollowSymLinks\n\tAllowOverride All\n\tRequire all granted\n</Directory>" >> /etc/apache2/apache2.conf

# 3. Préparation du code
WORKDIR /var/www/html
COPY . .

# 4. Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV APP_ENV=prod
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# 5. Gestion des dossiers de cache et logs avec permissions strictes
# On crée les dossiers et on donne la propriété à www-data AVANT de lancer quoi que ce soit
RUN mkdir -p var/cache var/log var/sessions \
    && chown -R www-data:www-data var/ \
    && chmod -R 775 var/

# 6. SCRIPT DE DÉMARRAGE CORRIGÉ
# On ajoute une ligne pour forcer les droits à chaque démarrage au cas où
RUN printf "#!/bin/sh\n\
echo 'Correction des droits...'\n\
chown -R www-data:www-data var/\n\
echo 'Lancement des migrations...'\n\
su www-data -s /bin/sh -c 'php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration'\n\
echo 'Démarrage d Apache...'\n\
apache2-foreground" > /usr/local/bin/app-entrypoint.sh

RUN chmod +x /usr/local/bin/app-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/app-entrypoint.sh"]