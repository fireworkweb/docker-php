FROM php:7.4-fpm-alpine

ENV ASUSER= \
    UID= \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PHP_MEMORY_LIMIT=256M \
    PHP_UPLOAD_MAX_FILESIZE=25M \
    PHP_POST_MAX_SIZE=25M \
    ENTRYPOINT=entrypoint.sh

WORKDIR /app

RUN adduser -D -u 1337 fwd \
    # dockerize
    && curl -L https://github.com/jwilder/dockerize/releases/download/v0.6.1/dockerize-alpine-linux-amd64-v0.6.1.tar.gz | tar xz \
    && mv dockerize /usr/local/bin/dockerize \
    # deps
    && apk --no-cache add su-exec bash git openssh-client icu shadow procps \
        freetype libpng libjpeg-turbo libzip-dev imagemagick \
        jpegoptim optipng pngquant gifsicle libldap \
    # build-deps
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        freetype-dev libpng-dev libjpeg-turbo-dev \
        icu-dev libedit-dev libxml2-dev \
        imagemagick-dev openldap-dev oniguruma-dev \
    # php-ext
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick-3.4.4 redis  \
    && docker-php-ext-enable imagick redis \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        calendar \
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
    && sed -i "s/user\ \=.*/user\ \= fwd/g" /usr/local/etc/php-fpm.d/www.conf \
    # composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && su-exec fwd composer global require hirak/prestissimo \
    # cleanup
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/* /home/fwd/.composer/cache

COPY fwd.ini /fwd/fwd.tmpl
COPY entrypoint /fwd/entrypoint
RUN chmod +x /fwd/entrypoint

EXPOSE 9000

ENTRYPOINT [ "dockerize", "-template", "/fwd/fwd.tmpl:/usr/local/etc/php/conf.d/fwd.ini", "/fwd/entrypoint" ]
CMD [ "php-fpm" ]
