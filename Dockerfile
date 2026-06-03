FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli mbstring fileinfo pdo_sqlite \
    && a2enmod rewrite \
    && sed -ri -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!<Directory /var/www/>!<Directory /var/www/html/public>!g' /etc/apache2/apache2.conf \
    && printf "<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>" > /etc/apache2/conf-available/laravel-public.conf \
    && a2enconf laravel-public

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data bootstrap/cache storage

RUN composer install --no-interaction --no-progress --no-dev --ignore-platform-reqs

RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && php -r "file_put_contents('.env', preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=sqlite', file_get_contents('.env')));" \
    && php -r "file_put_contents('.env', preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=/var/www/html/database/database.sqlite', file_get_contents('.env')));" \
    && mkdir -p database \
    && touch database/database.sqlite \
    && chmod 777 database/database.sqlite \
    && php artisan key:generate --ansi --force \
    && php artisan migrate --force \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan optimize:clear

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

EXPOSE 80
CMD ["apache2-foreground"]
