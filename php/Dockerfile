FROM php:7.3-apache-buster

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -y git wget nano zip unzip  \
    mariadb-server mariadb-client libicu-dev
RUN docker-php-ext-install intl opcache pdo pdo_mysql
RUN pecl install apcu && docker-php-ext-enable apcu

WORKDIR /var/www/GSB-Frais

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# install symfony
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv $HOME/.symfony/bin/symfony /usr/local/bin/symfony