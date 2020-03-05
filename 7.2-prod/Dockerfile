FROM php:7.2-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV ASUSER 0

ENV PHP_MEMORY_LIMIT 256M
ENV PHP_UPLOAD_MAX_FILESIZE 10M
ENV PHP_POST_MAX_SIZE 10M

WORKDIR /app

RUN wget https://github.com/jwilder/dockerize/releases/download/v0.6.1/dockerize-alpine-linux-amd64-v0.6.1.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-v0.6.1.tar.gz \
    && rm dockerize-alpine-linux-amd64-v0.6.1.tar.gz \
    && apk --no-cache add su-exec bash git openssh-client icu shadow procps \
        freetype libpng libjpeg-turbo libzip-dev imagemagick \
        jpegoptim optipng pngquant gifsicle libldap \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        freetype-dev libpng-dev libjpeg-turbo-dev \
        icu-dev libedit-dev libxml2-dev \
        imagemagick-dev openldap-dev \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick-3.4.4 redis \
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
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && apk del .build-deps \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/cache/apk/* /tmp/* /src

COPY fwd.ini /fwd/fwd.tmpl

RUN adduser -D -u 1337 fwd && \
    sed -i "s/user\ \=.*/user\ \= fwd/g" /usr/local/etc/php-fpm.d/www.conf && \
    su-exec fwd composer global require hirak/prestissimo

COPY entrypoint /entrypoint
RUN chmod +x /entrypoint

EXPOSE 9000

ENTRYPOINT [ "dockerize", "-template", "/fwd/fwd.tmpl:/usr/local/etc/php/conf.d/fwd.ini", "/entrypoint" ]
CMD [ "php-fpm" ]
