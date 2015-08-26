#!/bin/bash
/usr/bin/mysqld_safe > /dev/null 2>&1 &

RET=1
while [[ RET -ne 0 ]]; do
	echo "=> Waiting for confirmation of MySQL service startup"
	sleep 5
	mysql -uroot -e "status" > /dev/null 2>&1
	RET=$?
done

echo "=> Creating MySQL ghostylink user with ghostylink password"
mysql -uroot -e "CREATE USER 'ghostylink'@'localhost' IDENTIFIED BY 'ghostylink'"
mysql -uroot -e "CREATE DATABASE ghostylink_test"
mysql -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'ghostylink'@'localhost' WITH GRANT OPTION"

echo "=> Done!"
echo "========================================================================"
echo "You can now connect to this MySQL Server using:"
echo ""
echo " mysql -ughostylink -pghostylink -h<host> -P<port>"
echo ""
echo "Please remember to change the above password as soon as possible!"
echo "MySQL user 'root' has no password but only allows local connections"
echo "========================================================================"

/var/www/html/bin/cake migrations migrate

mysqladmin -uroot shutdown
