FROM alpine:edge

# use 1 run command to reduce container image layers
RUN apk --no-cache upgrade \
    && apk --no-cache add php84-fpm php84-curl php84-pdo_mysql php84-pdo_pgsql php84-mbstring php84-iconv php84-opcache \
        php84-phar php84-tokenizer php84-dom php84-simplexml php84-xml php84-xmlwriter \
        php84-pecl-redis php84-pecl-pcov msmtp \
    && rm /etc/php84/conf.d/pcov.ini \
    && adduser -u 82 -h /var/www -D -S -G www-data www-data \
    && mkdir -m 0777 /var/coverage \
    && mkfifo -m 0777 /var/log/php \
    && mv /usr/bin/php84 /usr/bin/php \
    && wget -O /usr/bin/composer https://github.com/composer/composer/releases/latest/download/composer.phar \
    && chmod +x /usr/bin/composer

# php global setting
COPY ./php.ini /etc/php84/php.ini

# php fpm settings
COPY ./php-fpm.conf /etc/php84/php-fpm.d/www.conf

# msmtp settings
COPY msmtp.conf /etc/msmtprc

USER www-data:www-data

WORKDIR /var/www

EXPOSE 9000

VOLUME /var/coverage

STOPSIGNAL SIGQUIT

HEALTHCHECK none

ENV PHP_CS_FIXER_IGNORE_ENV=1

CMD (tail -f /var/log/php &) ; (php-fpm84 -F)
