#!/bin/bash

scripts_dir="$(dirname "$0")"

source "$scripts_dir/db.sh"


sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}/" \
-e "s/^post_max_size.*/post_max_size = ${PHP_POST_MAX_SIZE}/" /etc/php5/apache2/php.ini

ghostylinkDir="/var/www/html/"

echo  "######################################################################"
echo  "##############     Ghostylink database initialization    #############"
echo  "######################################################################"
/usr/bin/mysqld_safe > /dev/null 2>&1 &
RET=1
echo -e "\t=> Waiting for confirmation of MySQL service startup\n"
while [[ RET -ne 0 ]]; do
    printf '.'
    sleep 5
    mysql -uroot -e "status" > /dev/null 2>&1
    RET=$?
done

if [[ ! db_volume_exist ]]; then
    echo -e "\t=> An empty or uninitialized MySQL volume is detected in $VOLUME_HOME"
    echo -e "\t=> Installing MySQL ..."
    mysql_install_db > /dev/null 2>&1
    echo -e "\t=> Done!"
    db_create  "$ghostylinkDir"
    db_upgrade "$ghostylinkDir"
else
    echo -e "\t=> Using an existing volume of MySQL"
    #TODO: Detect if the schema is "too" recent, and downgrade it if needed, (user warning ?)
    #Upgrade it if need
fi

mysqladmin -uroot shutdown

echo  "######################################################################"
echo  "##############   Ghostylink cron jobs initialization     #############"
echo  "######################################################################"
$ghostylinkDir/docker/init_crons.sh

exec supervisord -n
