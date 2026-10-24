FROM docker.io/library/debian:bookworm-slim@sha256:ec31d744f83f4a73447ba045aaab9409997efe5cc706eca4890c1be36cc1606a AS php-build

RUN apt-get update && apt-get install -y --no-install-recommends \
    apache2-dev build-essential ca-certificates curl libxml2-dev xz-utils \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /usr/src
# OpenSSL 1.0 is required by unmodified PHP 5.6. Link it statically into PHP.
RUN curl -fsSL https://www.openssl.org/source/old/1.0.2/openssl-1.0.2u.tar.gz -o openssl.tar.gz \
    && echo 'ecd0c6ffb493dd06707d38b14bb4d8c2288bb7033735606569d8f90f89669d16  openssl.tar.gz' | sha256sum -c - \
    && tar -xzf openssl.tar.gz \
    && cd openssl-1.0.2u \
    && ./config --prefix=/opt/openssl no-shared -fPIC \
    && make -j2 && make install_sw
RUN apt-get update && apt-get install -y --no-install-recommends zlib1g-dev \
    && curl -fsSL https://museum.php.net/php5/php-5.6.40.tar.xz -o php.tar.xz \
    && echo '1369a51eee3995d7fbd1c5342e5cc917760e276d561595b6052b21ace2656d1c  php.tar.xz' | sha256sum -c - \
    && tar -xf php.tar.xz \
    && cd php-5.6.40 \
    && CFLAGS='-O2 -fcommon' ./configure --prefix=/usr/local \
       --with-config-file-path=/usr/local/etc/php --with-apxs2=/usr/bin/apxs \
       --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-openssl=/opt/openssl \
       --with-zlib --enable-mbstring --disable-cgi --without-pear \
    && make -j2 && make install-cli
RUN install -D -m 755 /usr/src/php-5.6.40/libs/libphp5.so /usr/lib/apache2/modules/libphp5.so

FROM docker.io/library/debian:bookworm-slim@sha256:ec31d744f83f4a73447ba045aaab9409997efe5cc706eca4890c1be36cc1606a
LABEL org.opencontainers.image.source="https://github.com/ControlThings-io/dojo-control" \
      org.opencontainers.image.description="Disposable, intentionally vulnerable Dojo Control training lab"
RUN printf '#!/bin/sh\nexit 101\n' > /usr/sbin/policy-rc.d \
    && chmod 755 /usr/sbin/policy-rc.d \
    && apt-get update && apt-get install -y --no-install-recommends \
    apache2 mariadb-server libxml2 ca-certificates iputils-ping bind9-host curl \
    && rm -rf /var/lib/apt/lists/* \
    && a2dismod mpm_event && a2enmod mpm_prefork \
    && rm -f /var/www/html/index.html
COPY --from=php-build /usr/local/ /usr/local/
COPY --from=php-build /usr/lib/apache2/modules/libphp5.so /usr/lib/apache2/modules/libphp5.so
COPY --from=php-build /usr/src/php-5.6.40/LICENSE /usr/share/doc/dojo-control/PHP-LICENSE
COPY --from=php-build /usr/src/openssl-1.0.2u/LICENSE /usr/share/doc/dojo-control/OpenSSL-LICENSE
COPY LICENSE /usr/share/doc/dojo-control/LICENSE
COPY container/apache.conf /etc/apache2/conf-available/dojo.conf
COPY container/php.ini /usr/local/etc/php/php.ini
COPY container/entrypoint.sh /usr/local/bin/dojo-entrypoint
COPY web/ /var/www/html/
RUN a2dissite 000-default && a2enconf dojo \
    && chown -R www-data:www-data /var/www/html \
    && chmod 755 /usr/local/bin/dojo-entrypoint
WORKDIR /var/www/html
EXPOSE 80
# No VOLUME: replacing the container must discard the database and uploaded files.
ENTRYPOINT ["/usr/local/bin/dojo-entrypoint"]
