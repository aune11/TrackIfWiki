FROM debian:jessie

ENV DEBIAN_FRONTEND noninteractive

RUN echo "deb http://http.debian.net/debian/ jessie main contrib non-free" > /etc/apt/sources.list && \
  echo "deb http://http.debian.net/debian/ jessie-updates main contrib non-free" >> /etc/apt/sources.list && \
  echo "deb http://security.debian.org/ jessie/updates main contrib non-free" >> /etc/apt/sources.list

RUN apt-get update && \
  apt-get install -y ca-certificates curl libxml2 php5-memcached php5-mysqlnd php5-cli \
    php5-gd php5-curl git php5-dev php-pear libgearman-dev php5-redis php5-imagick && \
  pecl install gearman

RUN curl -sS https://getcomposer.org/installer | php && \
  mv composer.phar /usr/bin/composer