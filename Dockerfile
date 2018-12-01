FROM aayupov/php7-apache-mssql
MAINTAINER Ayupov Ayaz

COPY ./docker/php.ini /etc/php5/apache2/php.ini
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/
RUN a2enmod rewrite
EXPOSE 80