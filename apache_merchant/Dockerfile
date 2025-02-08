FROM php:7.4-apache

# Install MySQL client and ping
RUN apt-get update \
    && apt-get install -y default-mysql-client iputils-ping \
    && rm -rf /var/lib/apt/lists/*
    
RUN docker-php-ext-install mysqli && docker-php-ext-install pdo_mysql

RUN a2dismod ssl
RUN a2enmod rewrite

ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone