FROM alpine:edge

# use 1 run command to reduce container image layers
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories \
    && echo "http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories \
    apk --no-cache upgrade \
    && apk --no-cache add php86-fpm php86-curl php86-pdo_mysql php86-pdo_pgsql php86-mbstring \
        php86-phar php86-tokenizer php86-dom php86-simplexml php86-xml php86-xmlwriter \
        php86-pecl-redis php86-pecl-pcov msmtp \
    && rm /etc/php86/conf.d/pcov.ini \
    && adduser -u 82 -h /var/www -D -S -G www-data www-data \
    && mkdir -m 0777 /var/coverage \
    && mkfifo -m 0777 /var/log/php \
    && mv /usr/bin/php86 /usr/bin/php \
    && wget -O /usr/bin/composer https://github.com/composer/composer/releases/latest/download/composer.phar \
    && chmod +x /usr/bin/composer

# php global setting
COPY ./php.ini /etc/php86/php.ini

# php fpm settings
COPY ./php-fpm.conf /etc/php86/php-fpm.d/www.conf

# msmtp settings
COPY msmtp.conf /etc/msmtprc

USER www-data:www-data

WORKDIR /var/www

EXPOSE 9000

VOLUME /var/coverage

STOPSIGNAL SIGQUIT

HEALTHCHECK none

ENV PHP_CS_FIXER_IGNORE_ENV=1

CMD (tail -f /var/log/php &) ; (php-fpm86 -F)
