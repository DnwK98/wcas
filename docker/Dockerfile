FROM php:7.4-apache

# Install packages and php extensions
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
    pcntl \
    posix \
    zip \
 && a2enmod rewrite \
 && mkdir -p /var/vendor-build

# Build vendor first to decrease rebuild time
WORKDIR /var/vendor-build
COPY ./composer.lock /var/vendor-build/composer.lock
COPY ./composer.json /var/vendor-build/composer.json
RUN composer install

# Copy app files
WORKDIR /var/www/html
COPY . /var/www/html
COPY ./docker/fs /
RUN mv /var/vendor-build/vendor /var/www/html/vendor \
 && ls -la

# Set entrypoint
CMD ["/docker_entrypoint.sh"]
