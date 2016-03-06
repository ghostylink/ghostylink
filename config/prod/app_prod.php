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
        'private' => '6LdmCQwTAAAAAPqT9OWI2gHcUOHVrOFoy7WCagFS',
        'public' => '6LdmCQwTAAAAAEX9CazNNpFQyb7YjWob8QTqMtB2'
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
        'salt' => '56a5e2b007bdb03111cc49315b8439321fe30421cb86f9d7f4b90a7015f39d86',
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
