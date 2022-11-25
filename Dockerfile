FROM php:7-cli
ENV DD_PHP_TRACER_VERSION=0.74.0
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -Lf "https://github.com/DataDog/dd-trace-php/releases/download/${DD_PHP_TRACER_VERSION}/datadog-setup.php" > /tmp/datadog-setup.php \
 && php /tmp/datadog-setup.php --php-bin=all --enable-appsec --enable-profiling \
 && rm /tmp/datadog-setup.php

RUN apt update && apt-get install -y --no-install-recommends \
    unzip \
 && rm -rf /var/lib/apt/lists/* \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
