FROM php:5.6-apache as build_deps
WORKDIR /var/www/
COPY . ./
RUN apt-get update && apt-get install -y openssl zip unzip git libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql \
    && docker-php-ext-install pdo mbstring \
    && docker-php-ext-enable pdo mbstring
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN php composer.phar update && php composer.phar dumpautoload
RUN php composer.phar install 
EXPOSE 80
CMD ["php", "artisan", "serve", "--port=80"]

# FROM nginx
# COPY --from=build_deps /var/www /usr/share/nginx/html
# COPY default.conf /etc/nginx/conf.d
# EXPOSE 80
# CMD ["nginx", "-g", "daemon off;"]