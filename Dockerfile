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
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli mbstring fileinfo \
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
    && php artisan key:generate --ansi --force

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
