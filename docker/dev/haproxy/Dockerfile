
# This file is part of the ReactPHP Foundation <https://github.com/itnelo/reactphp-foundation>.
#
# (c) 2020 Pavel Petrov <itnelo@gmail.com>.
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @license https://opensource.org/licenses/mit MIT

ARG HAPROXY_VERSION

FROM haproxy:${HAPROXY_VERSION}

ARG TIMEZONE
ARG HAPROXY_PORT_EXPOSE

USER root

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    date

COPY haproxy.cfg /usr/local/etc/haproxy/haproxy.cfg

EXPOSE ${HAPROXY_PORT_EXPOSE}/tcp
