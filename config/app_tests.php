<?php
$testSource = [
    'className' => 'Cake\Database\Connection',
    'driver' => 'Cake\Database\Driver\Mysql',
    'persistent' => false,
    'host' => 'localhost',
    //'port' => 'nonstandard_port_number',
    'username' => 'ghostylink',
    'password' => 'ghostylink',
    'database' => 'ghostylink_test',
    'encoding' => 'utf8',
    'timezone' => 'UTC',
    'cacheMetadata' => true,
    'quoteIdentifiers' => false,
//'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
];
return [
    /**
     * Setting email catcher with maildev tool for testing purpose
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            // The following keys are used in SMTP transports
            'host' => getenv("CI_FROM_DOCKER") == "1" ? getenv("MAILDEV_HOST") : "localhost",
            'port' => getenv("CI_FROM_DOCKER") == "1" ? 25 : 1025,
            'webport' => getenv("CI_FROM_DOCKER") == "1" ? 80 : 1080,
            'timeout' => 30,
            'username' => null,
            'password' => null,
            'tls' => null
        ],
    ],
    'Datasources' => [
        /*
         * Set default connection for server used during selenium tests
         * 
         */
        'default' => $testSource,
        
        /**
         * The test connection is used during unit test suite.
         */
        'test' => $testSource,

        /**
         * The connection test template. Use to retrieve the schema
         */
        'test_schema' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            //'port' => 'nonstandard_port_number',
            'username' => 'ghostylink',
            'password' => 'ghostylink',
            'database' => 'ghostylink_test_template',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
        ]
    ],
    /**
     * Google recaptcha keys Testing specific
     * https://developers.google.com/recaptcha/docs/faq#im-currently-using-recaptcha-v1-how-do-i-upgrade-to-v2
     */
    'reCaptchaKeys' => [
        'private' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
        'public' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
    ],
    
];
