FROM php:7.2-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER 1
ARG ASUSER

WORKDIR /app

RUN apk update && apk add su-exec bash git openssh-client icu libpng freetype libjpeg-turbo libzip-dev shadow \
    && apk add --virtual build-dependencies icu-dev libxml2-dev freetype-dev \
        libpng-dev libjpeg-turbo-dev g++ make autoconf libsodium-dev libedit-dev curl-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        curl \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo \
        pdo_mysql \
        readline \
        soap \
        xml \
        zip \
    && cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && apk del build-dependencies \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/cache/apk/* /tmp/* /src

COPY fwd.ini $PHP_INI_DIR/conf.d/fwd.ini

RUN adduser -D -u 1337 developer && \
    sed -i "s/user\ \=.*/user\ \= developer/g" /usr/local/etc/php-fpm.d/www.conf && \
    su-exec developer composer global require hirak/prestissimo

COPY entrypoint /entrypoint
RUN chmod +x /entrypoint

EXPOSE 9000

ENTRYPOINT [ "/entrypoint" ]
CMD [ "php-fpm" ]
