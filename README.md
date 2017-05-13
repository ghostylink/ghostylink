# Ghostylink project

This page describe the ghostylink project.
The projet is based on [CakePHP 3](http://cakephp.org) framework.

| Branches        | Build status           | Development versions  |
| ------------- |:-------------:|:-----:|
| develop      |[![Build Status](http://jenkins.ghostylink.org/job/ghostylink/job/auto-scan/job/ghostylink/job/develop/badge/icon)](http://jenkins.ghostylink.org/job/ghostylink/job/auto-scan/job/ghostylink/job/develop/badge/icon) | [![Dependency Status](https://www.versioneye.com/user/projects/5707de57fcd19a00415b0f93/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5707de57fcd19a00415b0f93) |
| master     |[![Build Status](http://jenkins.ghostylink.org/job/ghostylink/job/auto-scan/job/ghostylink/job/master/badge/icon)](http://jenkins.ghostylink.org/job/ghostylink/job/auto-scan/job/ghostylink/job/master/badge/icon) | [![Dependency Status](https://www.versioneye.com/user/projects/5707de60fcd19a005185511c/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5707de60fcd19a005185511c) |
| hotfixes |      |     |


##Purpose
Ghostylink is an open sources project. It aims at helping people to share 
private information without writing it permanently on a mail or a chat system ...
User can create temporary link by specifying a limit (time, date or number of view).
When one of these limit is reached the link will be automaticaly deleted.

[![Ghostylink screenshot](http://doc.ghostylink.org/ghostylink-overview.png)](http://doc.ghostylink.org/ghostylink-overview.png)
## Web site

The current version of the ghostylink project can be found at 
[ghostylink.org](http://ghostylink.org)

## Self hosting
If you do not want to use the [ghostylink.org](http://ghostylink.org) server, 
you can choose to host the service on your own.

### Using docker image (recommended)
See the [ghostylink docker](https://github.com/ghostylink/docker) repository for hosting through docker

### From sources
Clone the project

```bash
git clone https://github.com/ghostylink/ghostylink
git checkout <version>
mkdir -p log tmp
php composer.phar install
cp config/prod/app_prod_template.php config/prod/app_prod.php
```

Enable production configuration by uncommenting the  following line:
```php
// Configure::load('prod/app_prod', 'default', true); // PRODUCTION_CONF
```

Assuming you already have a mysql database configured, 
you must then configure the following informations in `config/prod/app_prod.php`.

* Database access
* A security salt for password hashing

*Optional*

* Google recatpcha keys (disable it by setting ``reCaptcheKeys.public` to `null`)
 - Used for recaptcha component activation
* Smtp access (disable it by setting `EmailTransport.default.username` to `null`)
 - Used for ghostyfication alerts
 - Used for user's email verification

| Configuration key       | Description           | Required  |
| ------------- |:-------------|:-----:|
|`fullBaseUrl`      | ghostylink installed url | **Yes** |
|`Security.salt` | a salt for password hashing      |    **Yes** |
|`Datasources.default.username` | database username      |    **Yes** |
|`Datasources.default.password` | database password      |    **Yes** |
|`Datasources.default.database` | database name      |    **Yes** |
|`EmailTransport.default.host`     | smtp host      |   No |
|`EmailTransport.default.username`      | smtp username      |   No |
|`EmailTransport.default.password` | smtp password      |    No |
|`reCaptcheKeys.public`      | google recaptcha public key      |   No |
|`reCaptcheKeys.private`      | google recaptcha private key      |   No |

Finally initialize database with
```bash
./bin/cake migrations migrate
```

## Wiki
For more information you can check our wiki page [here](https://github.com/beljul/ghostylink/wiki/)

## Bug and issues
If you find any issue please open a bug on the
[here](https://github.com/beljul/ghostylink/issues)

## Contact developers
You can find all contributor of the project
[here](https://github.com/beljul/ghostylink/graphs/contributors)


