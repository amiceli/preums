FROM node:24-bookworm AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm i

COPY . .
RUN npm run build

FROM serversideup/php:8.4-frankenphp-bookworm

USER root

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    mbstring \
    intl \
    zip \
    bcmath \
    gd \
    pcntl

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

RUN composer install --no-dev --optimize-autoloader

COPY --from=frontend /app/public/build ./public/build

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

USER www-data

EXPOSE 8000

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
