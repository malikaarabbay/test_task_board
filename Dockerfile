FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath pcntl exif intl

# Установим Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

# Сначала копируем зависимости
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# Теперь копируем остальные файлы приложения
COPY . .

# Теперь эти папки уже есть
RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
