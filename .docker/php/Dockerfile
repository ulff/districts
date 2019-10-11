FROM php:7.2-fpm
     
RUN apt-get update \
    && apt-get install -y --no-install-recommends vim curl apt-utils \
    wget zip unzip \
    gnupg gnupg1 gnupg2 \
    git

RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m

WORKDIR /var/www