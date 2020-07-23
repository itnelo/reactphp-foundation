
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

ARG HOST_UID
ARG TIMEZONE
ARG DEPLOYMENT_PATH

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

# permissions
RUN chown www-data:www-data /var/www ${DEPLOYMENT_PATH} && \
    usermod -u ${HOST_UID} www-data

USER www-data

# Manage packages and configurations for local development using this environment:
# docker-compose run --rm --no-deps app cp config/parameters.yml.dev.dist config/parameters.yml
# docker-compose run --rm --no-deps app composer install

# See Dockerfile-standalone if you want to build an isolated and immutable container.

CMD ["bin/app"]
