FROM php:8.4.3

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install zip gd pdo pdo_mysql

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/

COPY . .

RUN chown -R www-data:www-data /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage 
RUN chmod -R 755 /var/www/bootstrap/cache

EXPOSE 8000

CMD ["php","artisan","serve","--host=0.0.0.0", "--port=8000"]