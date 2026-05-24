FROM unit:1.34.1-php8.4

RUN apt update && apt install -y \
    curl unzip git gnupg ca-certificates \
    libicu-dev libzip-dev libpng-dev \
    libjpeg-dev libfreetype6-dev libssl-dev libpq-dev

# Node.js 24
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt install -y nodejs

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install -j$(nproc) \
    pcntl opcache pdo pdo_mysql intl zip gd exif ftp bcmath

RUN docker-php-ext-install -j$(nproc) pdo_pgsql pgsql

RUN pecl install redis && docker-php-ext-enable redis

# PHP config
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "opcache.jit_buffer_size=256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/custom.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Cache layers
COPY package*.json ./
COPY composer.json composer.lock ./

RUN npm install
RUN composer install --no-scripts --prefer-dist --no-interaction

# App
COPY . .

RUN composer dump-autoload --optimize
RUN php artisan package:discover --ansi

# Front build
RUN npm run build

RUN mkdir -p storage bootstrap/cache \
    && chown -R unit:unit storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY unit.json /docker-entrypoint.d/unit.json

EXPOSE 8000

CMD ["unitd", "--no-daemon"]
