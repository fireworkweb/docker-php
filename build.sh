#! /bin/bash

docker build -t fireworkweb/app:7.1-alpine 7.1-alpine
docker build -t fireworkweb/app:7.1-alpine-wkhtmltopdf 7.1-alpine-wkhtmltopdf
docker build -t fireworkweb/app:7.2-alpine 7.2-alpine
docker build -t fireworkweb/app:7.2-alpine-wkhtmltopdf 7.2-alpine-wkhtmltopdf
docker build -t fireworkweb/app:7.3-alpine 7.3-alpine
docker build -t fireworkweb/app:7.3-alpine-wkhtmltopdf 7.3-alpine-wkhtmltopdf
