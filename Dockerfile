FROM ubuntu:trusty

RUN apt-get update -y && \
    apt-get install -y apache2 libapache2-mod-php5 php5-mysqlnd curl && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get clean

RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/

ADD composer.* /var/www/html/
RUN composer install

ADD . /var/www/html/

ADD config/apache2.conf /etc/apache2/apache2.conf

ENV APACHE_LOG_DIR   /var/log/apache2
ENV APACHE_LOCK_DIR  /var/run/apache2
ENV APACHE_PID_FILE  /var/run/apache2/apache2.pid
ENV APACHE_RUN_USER  www-data
ENV APACHE_RUN_GROUP www-data

# make cache dir writable
RUN mkdir cache
RUN chown www-data: cache

ADD docker-entrypoint.sh /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["apache2", "-DFOREGROUND"]
EXPOSE 80
