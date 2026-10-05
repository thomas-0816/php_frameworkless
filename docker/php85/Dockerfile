FROM alpine:edge

# use 1 run command to reduce container image layers
RUN apk --no-cache upgrade \
    && apk --no-cache add php85-fpm php85-curl php85-pdo_mysql php85-pdo_pgsql php85-mbstring php85-opcache \
        php85-phar php85-tokenizer php85-dom php85-simplexml php85-xml php85-xmlwriter \
        php85-pecl-redis php85-pecl-pcov msmtp \
    && rm /etc/php85/conf.d/pcov.ini \
    && adduser -u 82 -h /var/www -D -S -G www-data www-data \
    && mkdir -m 0777 /var/coverage \
    && mkfifo -m 0777 /var/log/php \
    && mv /usr/bin/php85 /usr/bin/php \
    && wget -O /usr/bin/composer https://github.com/composer/composer/releases/latest/download/composer.phar \
    && chmod +x /usr/bin/composer

# php global setting
COPY ./php.ini /etc/php85/php.ini

# php fpm settings
COPY ./php-fpm.conf /etc/php85/php-fpm.d/www.conf

# msmtp settings
COPY msmtp.conf /etc/msmtprc

USER www-data:www-data

WORKDIR /var/www

EXPOSE 9000

VOLUME /var/coverage

STOPSIGNAL SIGQUIT

HEALTHCHECK none

ENV PHP_CS_FIXER_IGNORE_ENV=1

CMD (tail -f /var/log/php &) ; (php-fpm85 -F)
