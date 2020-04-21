FROM php:7.3-apache
# debian, "lsb_release -sc"=buster

# Apt conf
RUN apt-get clean && apt-get update && BUILD=1
RUN apt-get install -y gnupg rsync git sqlite3 vim wget zip
# --allow-unauthenticated

# Apache conf
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/

# Php ext
RUN apt-get install -y libcurl4-openssl-dev libpng-dev libsodium-dev libxslt1-dev libzip-dev zlib1g-dev
RUN docker-php-ext-install curl gd intl opcache sockets sodium xsl zip

# PG & MS
RUN apt-get install -y libpq-dev
RUN docker-php-ext-install pgsql pdo_pgsql mysqli pdo_mysql

# Amqp php ext
RUN apt-get install -y amqp-tools librabbitmq-dev
RUN echo "yes" | pecl install amqp
RUN echo "extension=amqp.so" > /usr/local/etc/php/conf.d/amqp.ini

# Redis
# https://redis.io/topics/quickstart
# https://github.com/phpredis/phpredis
RUN apt install -y redis-server
RUN echo "no" | pecl install redis
RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

# Symfony cli
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Xdebug
WORKDIR /tmp
RUN wget https://xdebug.org/files/xdebug-3.0.2.tgz
RUN tar -xzf xdebug-3.0.2.tgz 
WORKDIR /tmp/xdebug-3.0.2
RUN phpize && ./configure --enable-xdebug && make && make install
RUN echo "zend_extension=$(find /usr/local -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

# Composer
RUN wget https://getcomposer.org/installer && php ./installer && mv ./composer.phar /usr/local/bin/composer

# Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt update && apt install -y npm yarn

# Vue
RUN npm install vue

# Clean
RUN rm -rf /var/lib/apt/lists/*

# Entry
COPY ./docker-php-entrypoint /usr/local/bin

WORKDIR /var/www