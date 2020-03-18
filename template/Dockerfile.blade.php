FROM {{ $from }}

ENV COMPOSER_ALLOW_SUPERUSER 1
ARG ASUSER
@unless ($prod)
ARG ENABLE_XDEBUG=false
@endunless

WORKDIR /app

RUN apk --no-cache add su-exec bash git openssh-client icu shadow procps \
        freetype libpng libjpeg-turbo libzip-dev imagemagick \
        jpegoptim optipng pngquant gifsicle libldap \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        freetype-dev libpng-dev libjpeg-turbo-dev \
        icu-dev libedit-dev libxml2-dev \
        imagemagick-dev openldap-dev{{ version_compare($version, '7.4', '>=') ? ' oniguruma-dev' : '' }} \
@if (version_compare($version, '7.4', '>='))
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
@else
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
@endif
    && export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && pecl install imagick-3.4.4 redis{{ ! $prod ? ' xdebug' : '' }} \
    && docker-php-ext-enable imagick redis \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        calendar \
        exif \
        gd \
        intl \
        ldap \
        mbstring \
@if ($prod)
        opcache \
@endif
        pcntl \
        pdo \
        pdo_mysql \
        readline \
        soap \
        xml \
        zip \
    && cp "$PHP_INI_DIR/php.ini-{{ $prod ? 'production' : 'development' }}" "$PHP_INI_DIR/php.ini" \
    && apk del .build-deps \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/cache/apk/* /tmp/* /src

COPY fwd.ini $PHP_INI_DIR/conf.d/fwd.ini

RUN adduser -D -u 1337 fwd && \
    sed -i "s/user\ \=.*/user\ \= fwd/g" /usr/local/etc/php-fpm.d/www.conf && \
    su-exec fwd composer global require hirak/prestissimo

COPY entrypoint /entrypoint
RUN chmod +x /entrypoint

EXPOSE 9000

ENTRYPOINT [ "/entrypoint" ]
CMD [ "php-fpm" ]
