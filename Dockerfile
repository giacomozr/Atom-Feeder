FROM php:7.3-cli

COPY . /usr/src/app
WORKDIR /usr/src/app

ENTRYPOINT ["php", "./app/index.php"]