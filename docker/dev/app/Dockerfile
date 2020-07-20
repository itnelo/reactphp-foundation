
# This file is part of the ReactPHP Foundation <https://github.com/itnelo/reactphp-foundation>.
#
# (c) 2020 Pavel Petrov <itnelo@gmail.com>.
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @license https://opensource.org/licenses/mit MIT

ARG PHP_VERSION

FROM php:${PHP_VERSION}-cli

ARG TIMEZONE
ARG DEPLOYMENT_PATH
ARG APP_SERVER_PORT_EXPOSE

WORKDIR ${DEPLOYMENT_PATH}

USER root

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini && \
    date

# xdebug
RUN pecl install xdebug-beta && docker-php-ext-enable xdebug
COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# pcntl
RUN docker-php-ext-install pcntl

COPY php.ini /usr/local/etc/php/conf.d/php.ini

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version

USER www-data

CMD ["bin/app"]
