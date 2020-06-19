FROM {{ $from }}

RUN apk add --no-cache nodejs npm yarn \
    g++ \
    libpng-dev \
    make \
    zlib-dev \
    python3
