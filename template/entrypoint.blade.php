#!/bin/sh
set -e

@unless ($prod)
if [ "$ENABLE_XDEBUG" == "true" ]; then
    docker-php-ext-enable xdebug >> /dev/null 2>&1

    if [ $? != "0" ]; then
        echo "[ERROR] An error happened enabling xdebug"

        exit 1
    fi
fi
@endunless

# Run as current user
CURRENT_USER=${ASUSER:-${UID:-0}}

if [ ! -z "$CURRENT_USER" ] && [ "$CURRENT_USER" != "0" ]; then
    usermod -u $CURRENT_USER fwd
fi

# Run entrypoint if provided
if [ ! -z "$ENTRYPOINT" ] && [ -f "$ENTRYPOINT" ]; then
    bash $ENTRYPOINT
fi

if [ "$1" = "bash" ] || [ "$1" = "php-fpm" ] || [ "$1" = "nginx" ] || [ "$1" = "supervisord" ]; then
    exec "$@"
else
    exec su-exec fwd "$@"
fi
