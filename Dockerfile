FROM php:5.6-apache
WORKDIR /var/www
COPY . ./
COPY php.ini /etc/php5/apache2
RUN sed -i '/jessie-updates/d' /etc/apt/sources.list
RUN apt-get -o Acquire::Check-Valid-Until=false update
RUN apt-get -qq update 
RUN apt-get -qq install -y curl git 
RUN docker-php-ext-install mbstring pdo pdo_mysql
RUN docker-php-ext-enable mbstring pdo pdo_mysql
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN php composer.phar install 
RUN php composer.phar update && php composer.phar dumpautoload
EXPOSE 80
CMD ["php","artisan","serve", "--port=80","--host=0.0.0.0"]