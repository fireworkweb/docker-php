FROM php:7.4-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER 1
ARG ASUSER

WORKDIR /app

RUN adduser -D -u 1337 fwd \
    && apk --no-cache add su-exec bash git openssh-client icu shadow procps \
        freetype libpng libjpeg-turbo libzip-dev imagemagick \
        jpegoptim optipng pngquant gifsicle libldap \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        freetype-dev libpng-dev libjpeg-turbo-dev \
        icu-dev libedit-dev libxml2-dev \
        imagemagick-dev openldap-dev oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick-3.4.4 redis \
    && docker-php-ext-enable imagick redis \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        ldap \
        mbstring \
        opcache \
        pcntl \
        pdo \
        pdo_mysql \
        readline \
        soap \
        xml \
        zip \
    && cp "/usr/local/etc/php/php.ini-production" "/usr/local/etc/php/php.ini" \
    && apk del .build-deps \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && su-exec fwd composer global require hirak/prestissimo \
    && rm -rf /var/cache/apk/* /tmp/* /src /home/fwd/.composer/cache

COPY fwd.ini /usr/local/etc/php/conf.d/fwd.ini
COPY zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY entrypoint /entrypoint
RUN chmod +x /entrypoint

EXPOSE 9000

ENTRYPOINT [ "/entrypoint" ]
CMD [ "php-fpm" ]
