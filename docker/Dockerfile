FROM php:7.4-apache

RUN apt-get update \
 && apt-get install -y \
    software-properties-common \
    libzip-dev \
    unzip \
    iproute2 \
    git \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && docker-php-ext-install \
    pdo_mysql \
    zip \
 && a2enmod rewrite

WORKDIR /var/www/html

COPY ./composer.lock /var/www/html/composer.lock
COPY ./composer.json /var/www/html/composer.json
RUN composer install

COPY . /var/www/html
COPY ./docker/fs /

CMD ["/docker_entrypoint.sh"]
