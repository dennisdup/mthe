FROM php:7.4-fpm

RUN useradd -m -p docker -G www-data -s /bin/bash docker

RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    zip supervisor \
    unzip libpng-dev --no-install-recommends \
    && pecl install xdebug soap \
    && docker-php-ext-install pdo_mysql zip exif soap

RUN docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN mkdir -p /var/log/supervisor

COPY ./docker/php/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/php/xdebug.ini /root/xdebug.ini

RUN docker-php-ext-enable xdebug \
    && cat /root/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /root/xdebug.ini

RUN mkdir /home/docker/.composer

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1


CMD ["/usr/bin/supervisord"]

RUN rm -rf /var/www/*
