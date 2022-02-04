FROM php:7.2-fpm

RUN useradd -m -p docker -G www-data -s /bin/bash docker

RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    unzip libpng-dev --no-install-recommends \
    && docker-php-ext-install pdo_mysql zip exif

RUN docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN mkdir /home/docker/.composer

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === 'c5b9b6d368201a9db6f74e2611495f369991b72d9c8cbd3ffbc63edff210eb73d46ffbfce88669ad33695ef77dc76976') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/bin \
    && php -r "unlink('composer-setup.php');" \
    && ln -s /bin/composer.phar /bin/composer \
    && mkdir /home/docker/.composer/cache \
    && chown -R docker:docker /home/docker/.composer \
    && chmod -R g+w /home/docker/.composer

RUN rm -rf /var/www/*
