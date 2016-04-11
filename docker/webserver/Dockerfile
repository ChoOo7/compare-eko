#FROM php:7-apache
FROM php:5-apache

RUN apt-get update && apt install -y wget nano vim git



RUN apt-get update  && apt-get update --fix-missing && apt-get install -y lftp openssl libpng12-dev libjpeg-dev libpq-dev git wget php5-apcu php5-igbinary zlib1g-dev zlib1g-dev zlib1g-dev libmcrypt-dev php5-mcrypt php5-mysql php5-cli php5-curl php5-xsl php5-json php-mail-mime php5-imagick php5-redis imagemagick mysql-client libxslt-dev php-pear

RUN git clone https://github.com/phadej/igbinary.git && \
	cd igbinary && \
	phpize && \
	./configure CFLAGS="-O2 -g" --enable-igbinary && \
	make && \
	make install && \
	cd .. && \
	rm -fr igbinary && \
	ln -s /etc/php5/mods-available/igbinary.ini /usr/local/etc/php/conf.d/

RUN docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr
RUN docker-php-ext-install mysqli xsl gd mbstring pdo pdo_mysql mcrypt pdo_pgsql ftp pcntl zip soap

RUN pecl install zip


# install phpredis extension
ENV PHPREDIS_VERSION 2.2.7

RUN curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && docker-php-ext-install redis


#PHP Configuration
ADD phpConf/timezone.ini /usr/local/etc/php/conf.d/timezone.ini
ADD phpConf/memory.ini /usr/local/etc/php/conf.d/memory.ini
ADD phpConf/apcu.ini /usr/local/etc/php/conf.d/apcu.ini

RUN pear channel-discover pear.windowsazure.com
RUN pear install --alldeps Mail_mimeDecode
RUN pear install --alldeps pear.windowsazure.com/WindowsAzure-0.3.1



RUN a2enmod rewrite env headers

RUN mkdir -p /mnt/phptemp
RUN chmod -R 777 /mnt/phptemp


RUN usermod -u 1000 www-data

RUN mkdir -p /var/www/
RUN /etc/init.d/apache2 restart



RUN a2enmod ssl


RUN usermod -u 1000 www-data

RUN mkdir -p /var/www/app
RUN /etc/init.d/apache2 restart

ADD run.sh /root/run.sh
RUN chmod 555 /root/run.sh

EXPOSE 80

CMD ["/root/run.sh"]
