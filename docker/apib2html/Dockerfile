# see https://github.com/funbox/blueprinter

FROM alpine:edge

RUN apk upgrade --no-cache \
    && apk add --no-cache npm nodejs \
    && npm install --global @funboxteam/blueprinter \
    && npm cache clean --force \
    && adduser -u 1000 -h /var/www -D -S -G www-data www-data

USER www-data:www-data

HEALTHCHECK none

ENTRYPOINT ["blueprinter"]
