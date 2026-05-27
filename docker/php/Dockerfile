FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql intl opcache zip

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/servers-api
COPY . .

RUN groupadd --gid 1000 symfony \
    && useradd --uid 1000 --gid 1000 --create-home --shell /bin/bash symfony
USER symfony

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
