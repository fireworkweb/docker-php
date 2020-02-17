#! /bin/bash

docker build --pull -t fireworkweb/php:7.1-alpine 7.1
docker build --pull -t fireworkweb/php:7.1-prod 7.1-prod
docker build --pull -t fireworkweb/php:7.2 7.2
docker build --pull -t fireworkweb/php:7.2-prod 7.2-prod
docker build --pull -t fireworkweb/php:7.3 7.3
docker build --pull -t fireworkweb/php:7.3-prod 7.3-prod
docker build --pull -t fireworkweb/php:7.4 7.4
docker build --pull -t fireworkweb/php:7.4-prod 7.4-prod
