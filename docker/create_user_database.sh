#!/bin/bash
/usr/bin/mysqld_safe > /dev/null 2>&1 &

RET=1
while [[ RET -ne 0 ]]; do
	echo "=> Waiting for confirmation of MySQL service startup"
	sleep 5
	mysql -uroot -e "status" > /dev/null 2>&1
	RET=$?
done

GHOSTYLINK_INSTALL_DIR="/var/www/html"
echo "=> Reading configuration from '$GHOSTYLINK_INSTALL_DIR'"
db_name=$(php -r '$conf = require "/var/www/html/config/prod/app_prod.php"; \
                  print_r($conf["Datasources"]["default"]["database"]);')
db_user=$(php -r '$conf = require "/var/www/html/config/prod/app_prod.php"; \
                  print_r($conf["Datasources"]["default"]["username"]);')
db_pwd=$(php -r '$conf = require "/var/www/html/config/prod/app_prod.php"; \
                 print_r($conf["Datasources"]["default"]["password"]);')

echo "=> Creating MySQL $db_user user with ghostylink $db_pwd"
mysql -uroot -e "CREATE USER '$db_user'@'localhost' IDENTIFIED BY '$db_pwd'"
mysql -uroot -e "CREATE DATABASE $db_name"
mysql -uroot -e "GRANT ALL PRIVILEGES ON $db_name.* TO '$db_user'@'localhost' WITH GRANT OPTION"

echo "=> Done!"
echo "========================================================================"
echo "You can now connect to this MySQL Server using:"
echo ""
echo " mysql -u$db_user -p$db_pwd -h<host> -P<port>"
echo ""
echo "Please remember to change the above password as soon as possible!"
echo "MySQL user 'root' has no password but only allows local connections"
echo "========================================================================"

$GHOSTYLINK_INSTALL_DIR/bin/cake migrations migrate

mysqladmin -uroot shutdown
