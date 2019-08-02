FROM fireworkweb/app:7.1-alpine

RUN apk add --no-cache wkhtmltopdf \
        ttf-dejavu ttf-droid ttf-freefont ttf-liberation ttf-ubuntu-font-family \
    && ln -s /usr/bin/wkhtmltoimage /usr/local/bin/wkhtmltoimage \
    && ln -s /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf
