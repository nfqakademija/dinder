FROM ongr/php-fpm:7.1

ARG LOCAL_USER_ID=1000
ARG LOCAL_GROUP_ID=1000

RUN pecl install xdebug

RUN set -xe && \
  groupadd -g $LOCAL_GROUP_ID -o -f user && \
  useradd --shell /bin/bash -u $LOCAL_USER_ID -g user -o -c "" -m user

COPY xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini
