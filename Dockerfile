FROM php:5.6-apache
WORKDIR /var/www
COPY . ./
RUN apt-get update && \
  apt-get install -y curl git freetds-dev freetds-bin freetds-common libdbd-freetds libsybdb5 libqt4-sql-tds libqt5sql5-tds libpq-dev && \
  ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/libsybdb.so && \
  ln -s /usr/lib/x86_64-linux-gnu/libsybdb.a /usr/lib/libsybdb.a && \
  docker-php-ext-install mbstring pdo pdo_mysql pdo_dblib mssql pdo_pgsql pgsql && \
  docker-php-ext-enable mbstring pdo pdo_mysql pdo_dblib pdo_pgsql pgsql && \
  docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
  docker-php-ext-configure mssql
RUN curl --silent --show-error https://getcomposer.org/installer | php

# Remove Composer memory limit to avoid out of memory problem when initializing container
RUN cd /usr/local/etc/php/conf.d/ && \
echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN php composer.phar install 
RUN php composer.phar update && php composer.phar dumpautoload
ADD freetds.conf /etc/freetds
ADD locales.conf /etc/freetds
RUN chgrp -R www-data /var/www/storage && \
  chgrp -R www-data /var/www/bootstrap/cache && \
  chmod -R 777 /var/www/storage

RUN php artisan cache:clear

EXPOSE 80
CMD ["php","artisan","serve", "--port=80","--host=0.0.0.0"]