# PHP Composer
FROM composer:latest AS vendor
WORKDIR /var/www
RUN if [ ! -d /.composer ]; then \
    mkdir /.composer; \
  fi
RUN chmod -R ugo+rw /.composer
COPY composer* ./
RUN composer install --no-interaction --prefer-dist --ignore-platform-reqs --optimize-autoloader --apcu-autoloader --ansi --no-scripts
    # --no-dev

LABEL maintainer="Şakir Mehmetoğlu"

FROM php:8.1-fpm-bullseye

ARG WWWUSER=1000
ARG WWWGROUP=1000

ARG USER_AND_GROUP=laravel

ARG NODE_VERSION=16

ARG deployment_env="Production"
ENV deployment_env=${deployment_env}

ENV DEBIAN_FRONTEND=noninteractive

ENV APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1

WORKDIR /var/www

# Zaman dilimi işlemleri
ARG TZ=Europe/Istanbul
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -yqq; \
    pecl channel-update pecl.php.net \
    # Gerekli programlar
    && apt-get install -yqq --no-install-recommends \
        apt-utils lsb-release ca-certificates apt-transport-https wget software-properties-common \
        gnupg gnupg2 \
        gosu git supervisor \
        libcap2-bin python2 mlocate  \
    # GNU Privacy Guard
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C \
    && apt-get install -yqq gpg-agent \
    # NodeJS
    && curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    # Yarn
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get install -yqq yarn \
    # MySQL ve MariaDB
    && apt-get install -yqq mariadb-client default-mysql-client

# **************************************************************************************************
# PHP ve gerekli kütüphaneleri
# **************************************************************************************************
RUN set -eux; pecl install apcu
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libzip-dev zip unzip && docker-php-ext-configure zip && docker-php-ext-install zip && docker-php-ext-enable zip
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq librabbitmq-dev && docker-php-ext-install bcmath sockets && pecl install amqp && rm -rf /tmp/pear && docker-php-ext-enable amqp
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libjpeg-dev libpng-dev libfreetype6-dev libwebp-dev && docker-php-ext-configure gd --prefix=/usr --with-jpeg --with-webp --with-freetype && docker-php-ext-install gd && docker-php-ext-enable gd
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq uuid-dev && pecl install uuid
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libsnmp-dev && docker-php-ext-install snmp && docker-php-ext-enable snmp
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libgmp3-dev && docker-php-ext-install gmp && docker-php-ext-enable gmp
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libtidy-dev && docker-php-ext-install tidy && docker-php-ext-enable tidy
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libbz2-dev && docker-php-ext-install bz2 && docker-php-ext-enable bz2
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq librdkafka-dev && pecl install rdkafka && docker-php-ext-enable rdkafka
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libmpdec-dev && pecl install decimal
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libvips-dev && pecl install vips
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libxml2-dev && docker-php-ext-install soap && docker-php-ext-enable soap
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq libxslt-dev && docker-php-ext-install xsl && docker-php-ext-enable xsl
# --------------------------------------------------
RUN set -eux; apt-get update -yqq &&  apt-get install -yqq cmake libz-dev zlib1g-dev && pecl install grpc protobuf && docker-php-ext-enable grpc protobuf && git clone -b v1.42.0 https://github.com/grpc/grpc && cd grpc && git submodule update --init && mkdir cmake/build && cd cmake/build && cmake ../.. && make protoc grpc_php_plugin && cd ../../.. && rm -rf grpc
ENV PATH "/grpc/cmake/build:${PATH}"
ENV PATH "/grpc/cmake/build/third_party/protobuf:${PATH}"
# --------------------------------------------------

RUN echo "****************************************************************************************************"
RUN which php
RUN php -v
RUN php -m
RUN echo "****************************************************************************************************"
# **************************************************************************************************

# Proje dosyaları kopyalanıyor
COPY . .
COPY --from=vendor /var/www/vendor ./vendor

RUN setcap "cap_net_bind_service=+ep" /usr/local/bin/php

# Gerekli dosyalar ilgili yerlere kopyalanıyor
COPY ./deployment/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./deployment/php.ini /usr/local/etc/php/conf.d/octane.ini
COPY ./deployment/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Kullanıcı İşlemleri
RUN groupadd --force -g $WWWGROUP $USER_AND_GROUP
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 $USER_AND_GROUP
RUN if [ ! -z "$WWWUSER" ]; then \
    usermod -u $WWWUSER $USER_AND_GROUP; \
  fi

# Dosya sahipliği işlemleri tamamlanıyor
RUN chgrp -R $USER_AND_GROUP ./storage/logs/ ./bootstrap/cache/

# Yardımcı komutlar hazırlanıyor
RUN chmod +x ./deployment/entrypoint.sh
RUN cat ./deployment/utilities.sh >> ~/.bashrc

# Güncelleme ve temizlik yapılıyor
RUN set -eux; apt-get update; apt-get upgrade -yqq
RUN apt-get -yqq autoremove --purge && apt-get clean all && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && rm /var/log/lastlog /var/log/faillog
RUN updatedb

EXPOSE 9000

ENTRYPOINT ["./deployment/entrypoint.sh"]
