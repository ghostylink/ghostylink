# Dockerfile based on lamp container
FROM tutum/lamp:latest

MAINTAINER Jullian Bellino <jullian.bellino@gmail.com>

# Aditional necessary extensions
RUN apt-get update && apt-get -y install curl php5-intl

# Restart apache
RUN service apache2 restart

## Download and install composer
RUN cd / && curl -s https://getcomposer.org/installer | php

## Copy sources
RUN rm -fr /var/www/html/
COPY ./ /var/www/html/

## Initializing production configuration
RUN cd  /var/www/html && \
        php /composer.phar update --no-dev && \
        sed -ie 's#\s*\/\/\(\s*.*PRODUCTION.*\)#\1#g' config/bootstrap.php && \
        ./bin/cake asset_compress.asset_compress build

# Setting rights and create tmp and log files if needed
RUN mkdir -p /var/www/html/tmp/ \
    && mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/ \    
    && chmod -R 775 /var/www/html/

EXPOSE 80
CMD ["/var/www/html/docker/launch.sh"]
