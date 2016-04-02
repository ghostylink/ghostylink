<?php
return [
    /**
     * Production mode
     */
    'debug' => false,
    /**
     * Full url base for link in mails. For docker images port is dinamicaly
     * retrieve. Replace by the actual value to increase performance or
     * if you do not use docker
     */
    'App' => [
        'fullBaseUrl' => '__FULL_URL'
    ],
    /**
     * Setting email connection
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            // The following keys are used in SMTP transports
            'host' => '__EMAIL_HOST',
            'port' => 25,
            'timeout' => 5,
            'username' => '__EMAIL_USERNAME',
            'password' => '__EMAIL_PASSWORD',
            'client' => null,
            'tls' => null,
        ],
    ],
    
    /**
     * SQL Database
     */
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            //'port' => 'nonstandard_port_number',
            'username' => '__DB_USERNAME',
            'password' => '__DB_PASSWORD',
            'database' => '__DB_DATABASE',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ]
    ],

    /**
     * Google recaptcha keys
     */
    'reCaptchaKeys' => [
        'private' => '__CAPTCHA_PRIVATE',
        'public' => '__CAPTCHA_PUBLIC'
    ],
    
    /**
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     * !!!! Make sure to define it only once !!!!
     */
    'Security' => [
        'salt' => '__SECURITY_SALT',
    ],
    
    /**
     * Docker specific configuration
     */
    'Docker' => [
        'crons' => [
            /* Periodicity of alert sending. Do not put two often to avoid
             * spamming your users */
            'ghostification' => [
                'mail' => '0 0 * * *'
            ],
            /**
             * Avoid 100% link life in the database.
             */
            'life_checker' => '* * * * *',
        ]
    ]
];
