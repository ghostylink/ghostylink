<?php
return [
    /**
     * Production mode
     */
    'debug' => false,

    /**
     * Setting email connection
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            // The following keys are used in SMTP transports
            'host' => 'localhost.localdomain',
            'port' => 25,
            'timeout' => 5,
            'username' => 'ghostylink@localhost',
            'password' => 'ghostylink',
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
            'username' => 'ghostylink',
            'password' => 'ghostylink',
            'database' => 'ghostylink',
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
        'private' => null,
        'public' => null
    ]
];
