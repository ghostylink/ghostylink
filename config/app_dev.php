<?php
return [
    /**
     * Full base url for url generation in mail
     */
    'App' => [
        'fullBaseUrl' => 'http://localhost:8765'
    ],
    /**
     * Setting email catcher with maildev tool for development purpose
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            // The following keys are used in SMTP transports
            'host' => 'localhost',
            'port' => 1025,
            'timeout' => 30,
            'username' => null,
            'password' => null,
            'tls' => null
        ],
    ],
];
