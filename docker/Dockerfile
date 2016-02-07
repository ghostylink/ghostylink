# Dockerfile based on lamp container
FROM tutum/lamp:latest

MAINTAINER Jullian Bellino <jullian.bellino@gmail.com>

# Download latest version of Ghostylink
RUN rm -fr /var/www/html/ && git clone -b master https://github.com/beljul/ghostylink /var/www/html/

# Download and install composer
RUN apt-get update && apt-get -y install curl
RUN cd /var/www/html && curl -s https://getcomposer.org/installer | php

# Need intl php extension
RUN apt-get install -y php5-intl
RUN apt-get install -y php5-curl
RUN service apache2 restart

# Update dependencies using composer
RUN cd /var/www/html && php composer.phar update

# Add database setup script
ADD create_user_database.sh /create_user_database.sh
RUN chmod 755 /create_user_database.sh

# Launch application 
ADD launch.sh /launch.sh
RUN chmod 755 /launch.sh

RUN chown -R www-data:www-data /var/www/html
RUN mkdir /var/www/html/tmp/ && chmod -R 777 /var/www/html/tmp/
RUN chmod -R 777 /var/www/html/vendor/
RUN mkdir /var/www/html/logs
RUN chmod -R 777 /var/www/html/logs

EXPOSE 80
CMD ["/launch.sh"]
