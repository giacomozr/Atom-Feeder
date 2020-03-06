FROM php:7.3-cli

COPY . /usr/src/app
WORKDIR /usr/src/app

RUN apt-get update && apt-get install -y libtidy-dev libzip-dev \
    && docker-php-ext-install tidy zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install

# Script entrypoint
ENTRYPOINT ["php", "./app/index.php"]