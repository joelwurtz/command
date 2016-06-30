FROM alpine:edge

# mirrors in france
RUN echo "http://mirrors.2f30.org/alpine/edge/main" > /etc/apk/repositories && \
    echo "http://mirrors.2f30.org/alpine/edge/community" >> /etc/apk/repositories && \
    echo "http://mirrors.2f30.org/alpine/edge/testing" >> /etc/apk/repositories

# Bash
RUN apk --update upgrade && apk add bash && rm -rf /var/cache/apk/*

# Php
RUN apk --update add \
    php7-apcu \
    php7-intl \
    php7-dom \
    php7-ctype \
    php7-json \
    php7-xml \
    php7-openssl \
    php7-posix \
    php7-pdo_mysql \
    php7-pdo_sqlite \
    php7-curl \
    php7-iconv \
    php7-zip \
    php7-sockets \
    php7-opcache \
    php7-pcntl \
    php7-ftp \
    php7 \
    && \
    rm -rf /var/cache/apk/*

RUN apk --update add php7-dev wget libssh2-dev build-base autoconf && \
    cd /tmp && \
    wget https://pecl.php.net/get/ssh2-1.0.tgz && \
    tar -xvzf ssh2-1.0.tgz && \
    cd ssh2-1.0 && \
    phpize7 && \
    ./configure --with-php-config=php-config7 && \
    make && \
    make install && \
    echo "extension=ssh2.so" > /etc/php7/conf.d/ssh2.ini && \
    cd / && \
    rm -rf /tmp/* && \
    apk del --purge php7-dev wget libssh2-dev build-base autoconf && \
    rm -rf /var/cache/apk/*
