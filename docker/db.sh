#!/bin/bash
if [[ $SCRIPT_DEV ]]; then
    set -x
fi

## Upgrade the database schema
## @param $1 ghostylink install directory
## @return void
function db_upgrade {
    local installDir=$1    
    $installDir/bin/cake migrations migrate
}

## Downgrade the database schema to the current last migration
## @param $1 ghostylink install directory
## @return void
function db_downgrade {
    local installDir=$1

    local savedPwd=$(pwd)
    local migrationsDir="$installDir/config/Migrations"
    local targetMigration=$(ls -1 -v $migrationsDir| grep -Po '^\d+'|tail -n 1)    
    printf "\t=> Checkout last known version to retrieve migrations\n"
    tmpDir=$(mktemp -d)
    cd $tmpDir    
    git clone git@github.com:ghostylink/ghostylink.git && cd ghostylink
    tmpDir="$tmpDir/ghostylink"
    mkdir -p $tmpDir/tmp $tmpDir/logs && chmod 777 $tmpDir/tmp && chmod $tmpDir/logs
    
    printf "\t => Installing dependencies and configuring\n"
    php $installDir/composer.phar install --no-interaction --no-dev
    cp $installDir/config/prod/app_prod.php $tmpDir/config/app_prod.php
    sed -ie 's#\s*\/\/\(\s*.*PRODUCTION.*\)#\1#g' $tmpDir/config/bootstrap.php

    printf "\t => Rollback database using prod configuration found in $installDir\n"    
    $tmpDir/bin/cake migrations rollback -t $targetMigration

    cd $savedPwd
}

## Check if db exist 
## @return true if the db exist, false otherwise
function db_volume_exist { 
    local VOLUME_HOME="/var/lib/mysql"
    if [[ ! -d $VOLUME_HOME/mysql ]]; then
        return 1
    else
        return 0
    fi
}

## Create the ghostylink database
## @param $1 ghostylink install directory
## @return void
function db_create {
    local installDir=$1
   
    printf "\t=> Reading configuration from '$installDir'"
    local db_name=$(db_get_conf_for "$installDir" "database")
    local db_user=$(db_get_conf_for "$installDir" "username")
    local db_pwd=$(db_get_conf_for "$installDir" "password")

    printf "\t=> Creating MySQL $db_user user with password $db_pwd"
    mysql -uroot -e "CREATE USER '$db_user'@'localhost' IDENTIFIED BY '$db_pwd'"
    printf "\t=> Creating MySQL database $db_name"
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
}

## Check if version A is before version B
## @param $1 version A
## @param $2 version B
## @return 1 if A < B, 2 if A > B, 0 if A == B
function db_version_cmp {
    if [[ "$1" < "$2" ]]; then
        return 1
    elif [[ "$1" > "$2" ]]; then
        return 2
    else
        return 0
    fi
}

## Check if a version is before an other
## @param $1 version supposed to be before
## @param $2 version supposed to be after
## @return true if $1 is before $2. False otherwise
function db_version_is_before {    
    $(db_version_cmp "$1" "$2")
    local ret=$?    
    if [[ $ret -eq 1 ]]; then        
        return 0
    else
        return 1
    fi    
}

## Check if a version is after an other
## @param $1 version supposed to be after
## @param $2 version supposed to be before
## @return true if $1 is after $2. False otherwise
function db_version_is_after {
    $(db_version_cmp "$1" "$2")
    local ret=$?    
    if [[ $ret -eq 2 ]]; then        
        return 0
    else
        return 1
    fi    
}

## Get current version of the migrations (in the database)
## @param $1 ghostylink install directory
## @return print to stdout the installed version 
function db_get_version {
    local installDir=$1
    local db_name=$(db_get_conf_for "$installDir" "database")
    local db_user=$(db_get_conf_for "$installDir" "username")
    local db_pwd=$(db_get_conf_for "$installDir" "password")
    
    # Do not print line header. Run in Batch mode
    version=$(mysql -u$db_user -p$db_pwd -N -B -e "use $db_name; \
                                      SELECT version \
                                      FROM phinxlog \
                                      ORDER BY version DESC LIMIT 1")
    echo $version
}

## Retrieve the current expected version in the container
## @param $1 ghostyink install directory
## @return print to stdout the expected migration version
function db_get_expected_version {
    local installDir=$1
    version=$(ls -1 -r -v $migrationsDir| grep -Po '^\d+'| head -n 1)
    echo $version
}

## Retrieve a configuration value
## @param $1 ghostylink install directory
## @param $2 key to retrieve
## @return print to stdout the value for the key in the conf file
function db_get_conf_for {
    local installDir=$1
    local key=$2
    local phpStatement="\$conf = require '$installDir/config/prod/app_prod.php'; \
                         print_r(\$conf['Datasources']['default']['$key']);"    
    local val=$(php -r "$phpStatement")
    echo "$val"
}