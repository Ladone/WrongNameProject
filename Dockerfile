FROM php:8.2-fpm

RUN set -eux; \
    apt update; \
    apt install -y \
        wget \
        libzip-dev \
        zip \
        vim \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libpq-dev; \
     php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
     php ./composer-setup.php; \
     mv ./composer.phar /usr/sbin/composer; \
    pecl install xdebug; \
    rm -rf /var/lib/apt/lists/*; \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/; \
    docker-php-ext-install gd; \
    docker-php-ext-enable xdebug; \
    docker-php-ext-configure zip; \
    docker-php-ext-install zip; \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql; \
    docker-php-ext-install pdo pdo_mysql

RUN echo "" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "[xdebug]" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/var/log/xdebug/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ARG PUID=1000
ARG PGID=1000

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN chown -R www-data:www-data /var/www/


USER www-data
WORKDIR /var/www/html
COPY composer.json ./composer.json
RUN composer install --no-dev --no-interaction --no-autoloader --no-scripts
RUN composer dump-autoload
