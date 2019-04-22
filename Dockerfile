FROM chriswayg/apache-php as build_deps
WORKDIR /var/www
COPY . ./
RUN sed -i '/jessie-updates/d' /etc/apt/sources.list
RUN apt-get -o Acquire::Check-Valid-Until=false update
RUN apt-get -qq update
RUN apt-get -qq install -y curl git nano
RUN curl --silent --show-error https://getcomposer.org/installer | php
# RUN php composer.phar update && php composer.phar dumpautoload
RUN php composer.phar install 

FROM nginx:1.10
COPY --from=build_deps /var/www /usr/share/nginx/html
COPY default.conf /etc/nginx/conf.d
ADD vhost.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]