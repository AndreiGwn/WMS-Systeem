FROM dunglas/frankenphp:latest

RUN install-php-extensions \
    pdo_mysql \
    gd \
    zip \
    opcache

WORKDIR /app
COPY . /app

RUN mkdir -p storage/app/public/tickets bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache
