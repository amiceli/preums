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

# Node 24
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

WORKDIR /var/www/html

ENV APP_ENV=production

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY package.json package-lock.json ./
RUN npm i

COPY --chown=www-data:www-data . .

RUN npm run build

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

USER www-data

EXPOSE 8000

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=80"]
