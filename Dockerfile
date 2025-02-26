FROM php:8.2

RUN apt-get update && apt-get install -y \
    git \
    sudo \
    zip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql


WORKDIR /php-crud-restful-api
COPY . .

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install


RUN chown -R www-data:www-data \
    /php-crud-restful-api/storage \
    /php-crud-restful-api/bootstrap/cache

CMD php artisan serve --host 0.0.0.0 --port=8000